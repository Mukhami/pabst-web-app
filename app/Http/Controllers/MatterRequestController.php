<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatterRequestRequest;
use App\Http\Requests\UpdateMatterRequestApprovalRequest;
use App\Http\Requests\UpdateMatterRequestRequest;
use App\Jobs\PostMatterRequestToImanage;
use App\Mail\MatterRequestDocketingTeamMail;
use App\Models\MatterRequest;
use App\Models\MatterRequestApproval;
use App\Models\MatterSubType;
use App\Models\MatterType;
use App\Models\User;
use App\Notifications\ApprovalRejectedNotification;
use App\Notifications\ChangesRequestedNotification;
use App\Notifications\MatterRequestStatusUpdateNotification;
use App\Notifications\NewMatterRequestAssignment;
use App\Notifications\UpdatedMatterRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class MatterRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', MatterRequest::class);

        return view('backend.matter-requests.index', [
            'title' => 'Matter Request Management',
            'sub_title' => 'List matter requests.',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', MatterRequest::class);

        $matter_types = MatterType::query()->orderBy('name')->where('status', '=', true)->get();
        $matter_sub_types = MatterSubType::query()->orderBy('name')->where('status', '=', true)->get();
        $responsible_attorneys = User::role('responsible_attorney')->where('status', '=', true)->get();
        $staff = User::role('general')->where('status', '=', true)->get();
        $partners = User::role('partner')->where('status', '=', true)->get();
        $entity_sizes = MatterRequest::ENTITY_SIZES;
        $conflict = User::role('conflict')->where('status', '=', true)->get();

        return view('backend.matter-requests.create', [
            'title' => 'Matter Request Management',
            'sub_title' => 'Add new Matter Request.',
            'matter_types' => $matter_types,
            'matter_sub_types' => $matter_sub_types,
            'responsible_attorneys' => $responsible_attorneys,
            'staff' => $staff,
            'partners' => $partners,
            'conflict' => $conflict,
            'entity_sizes' => $entity_sizes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMatterRequestRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // If not saving as a draft, validate crucial fields
        if (!$request->input('save_as_draft') || $request->input('save_as_draft') !== 'true') {
            $requiredFields = [
                'conflict_user_id' => 'Please select a conflict user to initiate approvals.',
                'partner_id' => 'Please select a partner.',
                'secondary_partner_id' => 'Please select a secondary partner.'
            ];

            foreach ($requiredFields as $field => $errorMessage) {
                if ($request->input($field) === null) {
                    return Redirect::back()
                        ->withInput()
                        ->withErrors([$field => $errorMessage])
                        ->with('warning', 'Kindly fill in all the required fields before submitting the Matter Request.');
                }
            }
        }

        $matterRequest = new MatterRequest();
        $matterRequest->fill($validatedData);
        $matterRequest->renewal_fees_handled_elsewhere = $request->input('renewal_fees_handled_elsewhere') === 'true';

        $matterRequest->responsible_attorney()->associate($request->input('responsible_attorney_id'));
        $matterRequest->matter_type()->associate($request->input('matter_type_id'));
        $matterRequest->matter_sub_type()->associate($request->input('sub_type_id'));
        $matterRequest->conductor()->associate(auth()->user());
        $matterRequest->conducted_date = now();

        if ($request->has('additional_staff_id') && $request->input('additional_staff_id') !== null){
            $matterRequest->additional_staff()->associate($request->input('additional_staff_id'));
        }

        if ($request->has('partner_id') && $request->input('partner_id') !== null){
            $matterRequest->partner()->associate($request->input('partner_id'));
        }

        if ($request->has('secondary_partner_id') && $request->input('secondary_partner_id') !== null){
            $matterRequest->secondary_partner()->associate($request->input('secondary_partner_id'));
        }

        if ($request->has('conflict_user_id') && $request->input('conflict_user_id') !== null){
            $matterRequest->conflict_user()->associate($request->input('conflict_user_id'));
        }

        // Check if the form is being saved as a draft
        if ($request->input('save_as_draft') === 'true') {
            $matterRequest->status = MatterRequest::STATUS_TYPE_DRAFT;
            $matterRequest->approval_flow_started = false;
        } else {
            $matterRequest->status = MatterRequest::STATUS_TYPE_SUBMITTED;
            $matterRequest->approval_flow_started = true;
        }

        $matterRequest->save();


        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $uploadedFile) {
                // Create a unique file name with the matterRequest ID
                $filename = $matterRequest->id . '-' . Str::slug( $uploadedFile->getClientOriginalName()) . '-' . mt_rand(1000,9999) . '.' . $uploadedFile->getClientOriginalExtension();
                // Store the file in the "attachments/matterRequestID" directory
                $path = $uploadedFile->storeAs('attachments/' . $matterRequest->id, $filename);
                $matterRequest->files()->create([
                    'name' => $filename,
                    'path' => $path,
                    'mime_type' => $uploadedFile->getClientMimeType(),
                    'size' => $uploadedFile->getSize(),
                ]);
            }
        }

        //disabled mail notification to RA
        //$responsibleAttorney = $matterRequest->responsible_attorney;
        //$responsibleAttorney->notify(new NewMatterRequestAssignment($matterRequest));

        if ($matterRequest->status === MatterRequest::STATUS_TYPE_SUBMITTED && $matterRequest->approval_flow_started){
            //Send Approval request to Conflict User
            $new_approval = MatterRequestApproval::create([
                'matter_request_id' => $matterRequest->id,
                'user_id' => $matterRequest->conflict_user_id,
                'approval_type' => MatterRequestApproval::TYPE_CONFLICTS_TEAM,
                'status' => MatterRequestApproval::STATUS_PENDING,
            ]);

            $new_approval->load('matter_request');

            $conflictUser = $matterRequest->conflict_user;

            Notification::send($conflictUser, new MatterRequestStatusUpdateNotification($new_approval));

            return Redirect::route('matter-requests.index')->with('success', "$matterRequest->title_of_invention created successfully, and an Approval request has been sent to $conflictUser->name!");
        } else {
            return Redirect::route('matter-requests.index')->with('success', "$matterRequest->title_of_invention created successfully, and Saved as a DRAFT");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MatterRequest $matterRequest): View
    {
        Gate::authorize('view', $matterRequest);

        $matterRequest->load([
            'matter_type',
            'files',
            'matter_sub_type',
            'responsible_attorney',
            'additional_staff',
            'conductor',
            'matter_request_approvals'
        ]);

        return view('backend.matter-requests.show', [
            'title' => 'Matter Request Management',
            'sub_title' => 'View Matter Request.',
            'matterRequest' => $matterRequest
        ]);

    }

    /**
     * Download Matter Request PDF the specified resource.
     */
    public function downloadPDF(MatterRequest $matterRequest): \Illuminate\Http\Response
    {
        Gate::authorize('view', $matterRequest);

        $matterRequest->load([
            'matter_type',
            'matter_sub_type',
            'responsible_attorney',
            'additional_staff',
            'conductor',
            'matter_request_approvals'
        ]);

        $pdf = Pdf::loadView('pdf.view', ['matterRequest' => $matterRequest]);

        return $pdf->download( "Matter-Request-$matterRequest->ppg_client_matter_no.pdf");
    }

    /**
     * Download Matter Request PDF the specified resource.
     */
    public function viewPDF(MatterRequest $matterRequest): View
    {
        Gate::authorize('view', $matterRequest);

        $matterRequest->load([
            'matter_type',
            'matter_sub_type',
            'responsible_attorney',
            'additional_staff',
            'conductor',
            'matter_request_approvals'
        ]);

        return view('pdf.view', [
            'matterRequest' => $matterRequest
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MatterRequest $matterRequest): View
    {
        Gate::authorize('update', $matterRequest);
        $matterRequest->load(['conductor', 'files']);
        $matter_types = MatterType::query()->orderBy('name')->where('status', '=', true)->get();
        $matter_sub_types = MatterSubType::query()->orderBy('name')->where('status', '=', true)->get();
        $responsible_attorneys = User::role('responsible_attorney')->where('status', '=', true)->get();
        $staff = User::role('general')->where('status', '=', true)->get();
        $conflict = User::role('conflict')->where('status', '=', true)->get();
        $partners = User::role('partner')->where('status', '=', true)->get();
        $entity_sizes = MatterRequest::ENTITY_SIZES;

        return view('backend.matter-requests.edit', [
            'title' => 'Matter Request Management',
            'sub_title' => 'Update Matter Request Details.',
            'matterRequest' => $matterRequest,
            'matter_types' => $matter_types,
            'matter_sub_types' => $matter_sub_types,
            'responsible_attorneys' => $responsible_attorneys,
            'staff' => $staff,
            'conflict' => $conflict,
            'partners' => $partners,
            'entity_sizes' => $entity_sizes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMatterRequestRequest $request, MatterRequest $matterRequest): RedirectResponse
    {
        $validatedData = $request->validated();
        // If not saving as a draft, validate crucial fields
        if (!$request->input('save_as_draft') || $request->input('save_as_draft') !== 'true') {
            $requiredFields = [
                'conflict_user_id' => 'Please select a conflict user to initiate approvals.',
                'partner_id' => 'Please select a partner.',
                'secondary_partner_id' => 'Please select a secondary partner.'
            ];

            foreach ($requiredFields as $field => $errorMessage) {
                if ($request->input($field) === null) {
                    return Redirect::back()
                        ->withInput()
                        ->withErrors([$field => $errorMessage])
                        ->with('warning', 'Kindly fill in all the required fields before submitting the Matter Request.');
                }
            }
        }

        $matterRequest->fill($validatedData);
        $matterRequest->renewal_fees_handled_elsewhere = $request->input('renewal_fees_handled_elsewhere') === 'true';

        $matterRequest->responsible_attorney()->associate($request->input('responsible_attorney_id'));
        $matterRequest->matter_type()->associate($request->input('matter_type_id'));
        $matterRequest->matter_sub_type()->associate($request->input('sub_type_id'));

        if ($request->has('additional_staff_id') && $request->input('additional_staff_id') !== null){
            $matterRequest->additional_staff()->associate($request->input('additional_staff_id'));
        }

        if ($request->has('partner_id') && $request->input('partner_id') !== null){
            $matterRequest->partner()->associate($request->input('partner_id'));
        }

        if ($request->has('secondary_partner_id') && $request->input('secondary_partner_id') !== null){
            $matterRequest->secondary_partner()->associate($request->input('secondary_partner_id'));
        }

        if ($request->has('conflict_user_id') && $request->input('conflict_user_id') !== null){
            $matterRequest->conflict_user()->associate($request->input('conflict_user_id'));
        }

        // Check if the form is being saved as a draft
        if ($request->input('save_as_draft') === 'true') {
            $matterRequest->status = MatterRequest::STATUS_TYPE_DRAFT;
        } else {
            $matterRequest->status = MatterRequest::STATUS_TYPE_SUBMITTED;
        }

        $matterRequest->save();

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $uploadedFile) {
                // Create a unique file name with the matterRequest ID
                $filename = $matterRequest->id . '-' . Str::slug( $uploadedFile->getClientOriginalName()) . '-' . mt_rand(1000,9999) . '.' . $uploadedFile->getClientOriginalExtension();
                // Store the file in the "attachments/matterRequestID" directory
                $path = $uploadedFile->storeAs('attachments/' . $matterRequest->id, $filename);
                $matterRequest->files()->create([
                    'name' => $filename,
                    'path' => $path,
                    'mime_type' => $uploadedFile->getClientMimeType(),
                    'size' => $uploadedFile->getSize(),
                ]);
            }
        }

        //disabled mail notification to RA
        //$responsibleAttorney = $matterRequest->responsible_attorney;
        //$responsibleAttorney->notify(new UpdatedMatterRequest($matterRequest));

        if ($matterRequest->status === MatterRequest::STATUS_TYPE_SUBMITTED && !$matterRequest->approval_flow_started){
            $matterRequest->approval_flow_started = true;
            $matterRequest->save();
            //Send Approval request to Conflict User
            $new_approval = MatterRequestApproval::create([
                'matter_request_id' => $matterRequest->id,
                'user_id' => $matterRequest->conflict_user_id,
                'approval_type' => MatterRequestApproval::TYPE_CONFLICTS_TEAM,
                'status' => MatterRequestApproval::STATUS_PENDING,
            ]);

            $new_approval->load('matter_request');

            $conflictUser = $matterRequest->conflict_user;

            Notification::send($conflictUser, new MatterRequestStatusUpdateNotification($new_approval));

            return Redirect::route('matter-requests.index')->with('success', "$matterRequest->title_of_invention updated successfully, and an Approval request has been sent to $conflictUser->name!");
        } else {
            return Redirect::route('matter-requests.index')->with('success', "$matterRequest->title_of_invention updated successfully");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatterRequest $matterRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function postApproval(UpdateMatterRequestApprovalRequest $request): RedirectResponse
    {
        $approval = MatterRequestApproval::with(['matter_request'])->find($request->input('approval_id'));

        $additional_remark = '';
        if ($approval->user_id != auth()->id()){
            $additional_remark = PHP_EOL . 'Review submitted by '.auth()->user()->name;
        }

        $approval->update([
            'status' => $request->input('status'),
            'remarks' => $request->input('remarks') . $additional_remark,
            'submitted_at' => now()
        ]);

        $approval->refresh();

        $matterRequest = $approval->matter_request;

        $matterRequest->load([
            'conflict_user',
            'conductor',
            'responsible_attorney', 'partner',
            'secondary_partner'
        ]);

        // Handle different statuses
        switch ($approval->status) {
            case MatterRequestApproval::STATUS_APPROVED:
                // Determine the next approver based on the current approval type
                switch ($approval->approval_type) {
                    case MatterRequestApproval::TYPE_CONFLICTS_TEAM:
                        // Route back to Partner
                        $new_approval = MatterRequestApproval::create([
                            'matter_request_id' => $matterRequest->id,
                            'user_id' => $matterRequest->partner_id,
                            'approval_type' => MatterRequestApproval::TYPE_PARTNER,
                            'status' => MatterRequestApproval::STATUS_PENDING,
                        ]);

                        $new_approval->load('matter_request');

                        $partner = $matterRequest->partner;

                        Notification::send($partner, new MatterRequestStatusUpdateNotification($new_approval));

                        break;

                    case MatterRequestApproval::TYPE_PARTNER:
                        if (!$matterRequest->conflict_user_id){
                            return Redirect::back()->withInput()->with('error', 'Kindly ensure a conflict user is attached to the Matter Request before proceeding');
                        }
                        // Route to Conflicts Team
                        $new_approval = MatterRequestApproval::create([
                            'matter_request_id' => $matterRequest->id,
                            'user_id' => $matterRequest->secondary_partner_id,
                            'approval_type' => MatterRequestApproval::TYPE_SECONDARY_PARTNER,
                            'status' => MatterRequestApproval::STATUS_PENDING,
                        ]);

                        $new_approval->load('matter_request');

                        $secondary_partner = $matterRequest->secondary_partner;

                        Notification::send($secondary_partner, new MatterRequestStatusUpdateNotification($new_approval));

                        break;

//                    case MatterRequestApproval::TYPE_RESPONSIBLE_ATTORNEY_FINAL:
//                        // Route to Secondary Partner
//                        $new_approval = MatterRequestApproval::create([
//                            'matter_request_id' => $matterRequest->id,
//                            'user_id' => $matterRequest->partner_id,
//                            'approval_type' => MatterRequestApproval::TYPE_SECONDARY_PARTNER,
//                            'status' => MatterRequestApproval::STATUS_PENDING,
//                        ]);
//
//                        $new_approval->load('matter_request');
//                        $partner = $matterRequest->partner;
//                        Notification::send($partner, new MatterRequestStatusUpdateNotification($new_approval));
//
//                        break;

                    case MatterRequestApproval::TYPE_SECONDARY_PARTNER:
;                       // SEND EMAIL TO: docketing@pabstpatent.com
                        Mail::to('docketing@pabstpatent.com')
                            ->cc('wmarita@mkenga.com')
                            ->cc('kmukhami@mkenga.com')
                            ->send(new MatterRequestDocketingTeamMail($matterRequest));

                        $matterRequest->approved = true;
                        $matterRequest->save();
                        break;
                }
                break;

            case MatterRequestApproval::STATUS_REJECTED:
                // Notify original submitter about rejection
                $originalSubmitter = $matterRequest->conductor;
                $responsibleAttorney = $matterRequest->responsible_attorney;
                Notification::send([$originalSubmitter, $responsibleAttorney], new ApprovalRejectedNotification($approval));

                MatterRequestApproval::create([
                    'matter_request_id' => $matterRequest->id,
                    'user_id' => $approval->user_id,
                    'approval_type' => $approval->approval_type,
                    'status' => MatterRequestApproval::STATUS_PENDING,
                ]);
                break;

            case MatterRequestApproval::STATUS_CHANGES_REQUESTED:
                // Notify responsible person for changes
                $responsiblePerson = $matterRequest->responsible_attorney;
                $originalSubmitter = $matterRequest->conductor;

                // Notification logic here
                Notification::send([$responsiblePerson, $originalSubmitter], new ChangesRequestedNotification($approval));

                MatterRequestApproval::create([
                    'matter_request_id' => $matterRequest->id,
                    'user_id' => $approval->user_id,
                    'approval_type' => $approval->approval_type,
                    'status' => MatterRequestApproval::STATUS_PENDING,
                ]);
                break;
        }
        return Redirect::back()->with('success', 'Approval has been submitted successfully.');
    }

    public function sendToImanage(MatterRequest $matterRequest): RedirectResponse
    {
        $user = User::find(auth()->id());

        PostMatterRequestToImanage::dispatch($matterRequest, $user);

        return Redirect::back()->with('success', "Matter Request Ref:$matterRequest->ppg_ref has been queued to be sent to imanage, you will receive an email confirmation once done.");
    }

    /**
     * Matter Requests DataTable
     * @return JsonResponse
     */
    public function matterRequestsData(): JsonResponse
    {
        Gate::authorize('viewAny', MatterRequest::class);

        $query = MatterRequest::query()
            ->select('matter_requests.*', 'user_resp_attorney.name as resp_attorney', 'user_conductor.name as conductor')
            ->leftJoin('users as user_resp_attorney', 'user_resp_attorney.id','=','matter_requests.responsible_attorney_id')
            ->leftJoin('users  as user_conductor', 'user_conductor.id','=','matter_requests.conducted_by')
            ->with(['matter_request_approvals']);

        return $this->matterRequestJsonData($query);

    }

    /**
     * Matter Requests DataTable that have a pending approval for the authenticated user
     * @return JsonResponse
     */
    public function usersPendingMatterRequestsData(): JsonResponse
    {
        Gate::authorize('viewAny', MatterRequest::class);
        $query = MatterRequest::query()
            ->select('matter_requests.*', 'user_resp_attorney.name as resp_attorney', 'user_conductor.name as conductor')
            ->leftJoin('users as user_resp_attorney', 'user_resp_attorney.id','=','matter_requests.responsible_attorney_id')
            ->leftJoin('users as user_conductor', 'user_conductor.id','=','matter_requests.conducted_by')
            ->whereHas('matter_request_approvals', function ($q){
                $q->where('matter_request_approvals.user_id', '=', auth()->id());
                $q->where('matter_request_approvals.status', '=', MatterRequestApproval::STATUS_PENDING);
            })
            ->with(['matter_request_approvals']);

        return $this->matterRequestJsonData($query);
    }

    /**
     * Matter Request DataTable Response
     * @param $query
     * @return JsonResponse
     */
    private function matterRequestJsonData($query): JsonResponse
    {
        return DataTables::eloquent($query)
            ->addColumn('status', function ($query) {
                if ($query->approved && $query->matter_create_response != null){
                    return '<div class="badge bg-primary rounded-pill">Synced to iManage</div>';
                }
                $approvals = $query->matter_request_approvals;
                if ($approvals->count() > 0){
                    $approval = $approvals->last();
                    if ($approval->status === MatterRequestApproval::STATUS_PENDING){
                        return '<div class="badge bg-warning rounded-pill">Pending</div>';
                    } elseif ($approval->status === MatterRequestApproval::STATUS_APPROVED){
                        return '<div class="badge bg-success rounded-pill">Approved</div>';
                    } elseif ($approval->status === MatterRequestApproval::STATUS_REJECTED){
                        return '<div class="badge bg-danger rounded-pill">Rejected</div>';
                    } elseif ($approval->status === MatterRequestApproval::STATUS_CHANGES_REQUESTED){
                        return '<div class="badge bg-info rounded-pill">Changes Requested</div>';
                    }
                }
                elseif ($query->status = MatterRequest::STATUS_TYPE_DRAFT){
                    return '<div class="badge bg-info rounded-pill">Draft</div>';
                }
                else {
                    return '<div class="badge bg-warning rounded-pill">Pending</div>';
                }
            })
            ->addColumn('action', function ($query) {
                $action =  '<a class="btn btn-light btn-sm me-2 p-1" href="'.route('matter-requests.show', $query).'">View &nbsp; <i class="fa-regular fa-eye"></i></a>';
                if (!$query->approved && $query->matter_create_response == null){
                    $action .= '<a class="btn btn-warning btn-sm me-2 p-1 mt-1" href="'.route('matter-requests.edit', $query).'">Edit &nbsp; <i class="fa-regular fa-edit"></i></a>';
                }
                if ((auth()->user()->hasRole('admin') || auth()->user()->hasRole('responsible_attorney')) && ($query->approved && $query->matter_create_response == null) ){
                    $action .= '<a class="btn btn-success btn-sm me-2 p-1" href="'.route('matter-requests.sendToImanage', $query).'">Send to iManage &nbsp; <i class="fa-regular fa-eye"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    /**
     * Matter Request Approvals DataTable
     * @param MatterRequest $matterRequest
     * @return JsonResponse
     */
    public function matterRequestApprovalsData(MatterRequest $matterRequest): JsonResponse
    {
        Gate::authorize('viewAny', MatterRequest::class);

        $query = MatterRequestApproval::query()
            ->select('matter_request_approvals.*', 'users.name as user')
            ->leftJoin('users', 'users.id','=','matter_request_approvals.user_id')
            ->where('matter_request_approvals.matter_request_id', '=', $matterRequest->id);

        return DataTables::eloquent($query)
            ->addColumn('user', function ($query) {
                if ($query->user){
                    return $query->user;
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('approval_type', function ($query) {
                return ucwords(str_replace('_', ' ', $query->approval_type));
            })
            ->editColumn('status', function ($query) {
                if ($query->status === MatterRequestApproval::STATUS_PENDING){
                    return '<div class="badge bg-warning rounded-pill">Pending</div>';
                } elseif ($query->status === MatterRequestApproval::STATUS_APPROVED){
                    return '<div class="badge bg-success rounded-pill">Approved</div>';
                } elseif ($query->status === MatterRequestApproval::STATUS_REJECTED){
                    return '<div class="badge bg-danger rounded-pill">Rejected</div>';
                } elseif ($query->status === MatterRequestApproval::STATUS_CHANGES_REQUESTED){
                    return '<div class="badge bg-info rounded-pill">Changes Requested</div>';
                }
            })
            ->addColumn('action', function ($query) {
                if ((auth()->id() === $query->user_id or auth()->user()->hasRole('admin')) and $query->status === MatterRequestApproval::STATUS_PENDING) {
                    return '<button type="button" class="btn btn-sm btn-primary create-approval" data-approval_id="'.$query->id.'" data-bs-toggle="modal" data-bs-target="#createApprovalModal">
                            Create Approval
                        </button>';
                }
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
}
