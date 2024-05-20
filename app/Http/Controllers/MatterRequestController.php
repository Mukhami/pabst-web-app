<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatterRequestRequest;
use App\Http\Requests\UpdateMatterRequestApprovalRequest;
use App\Http\Requests\UpdateMatterRequestRequest;
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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
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

        $matter_types = MatterType::query()->where('status', '=', true)->get();
        $matter_sub_types = MatterSubType::query()->where('status', '=', true)->get();
        $responsible_attorneys = User::role('responsible_attorney')->where('status', '=', true)->get();
        $staff = User::role('general')->where('status', '=', true)->get();
        $partners = User::role('partner')->where('status', '=', true)->get();

        return view('backend.matter-requests.create', [
            'title' => 'Matter Request Management',
            'sub_title' => 'Add new Matter Request.',
            'matter_types' => $matter_types,
            'matter_sub_types' => $matter_sub_types,
            'responsible_attorneys' => $responsible_attorneys,
            'staff' => $staff,
            'partners' => $partners,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMatterRequestRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
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
        $matterRequest->save();

        $responsibleAttorney = $matterRequest->responsible_attorney;

        $responsibleAttorney->notify(new NewMatterRequestAssignment($matterRequest));

        $matterRequestApproval = new MatterRequestApproval();

        $matterRequestApproval->fill([
            'status' => MatterRequestApproval::STATUS_PENDING,
            'approval_type' => 'responsible_attorney',
        ]);
        $matterRequestApproval->matter_request()->associate($matterRequest);
        $matterRequestApproval->user()->associate($responsibleAttorney);
        $matterRequestApproval->save();

        return Redirect::route('matter-requests.index')->with('success', "$matterRequest->title_of_invention created successfully, and an Approval request has been sent to $responsibleAttorney->name!");
    }

    /**
     * Display the specified resource.
     */
    public function show(MatterRequest $matterRequest): View
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

        return view('backend.matter-requests.show', [
            'title' => 'Matter Request Management',
            'sub_title' => 'View Matter Request.',
            'matterRequest' => $matterRequest
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MatterRequest $matterRequest): View
    {
        Gate::authorize('update', $matterRequest);

        $matter_types = MatterType::query()->where('status', '=', true)->get();
        $matter_sub_types = MatterSubType::query()->where('status', '=', true)->get();
        $responsible_attorneys = User::role('responsible_attorney')->where('status', '=', true)->get();
        $staff = User::role('general')->where('status', '=', true)->get();
        $conflict = User::role('conflict')->where('status', '=', true)->get();
        $partners = User::role('partner')->where('status', '=', true)->get();

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
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMatterRequestRequest $request, MatterRequest $matterRequest): RedirectResponse
    {
        $validatedData = $request->validated();
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

        if ($request->has('docketing_user_id') && $request->input('docketing_user_id') !== null){
            $matterRequest->docketing_user()->associate($request->input('docketing_user_id'));
        }
        if ($request->has('conflict_user_id') && $request->input('conflict_user_id') !== null){
            $matterRequest->conflict_user()->associate($request->input('conflict_user_id'));
        }

        $matterRequest->save();

        $responsibleAttorney = $matterRequest->responsible_attorney;

        $responsibleAttorney->notify(new UpdatedMatterRequest($matterRequest));

        return Redirect::route('matter-requests.index')->with('success', "$matterRequest->title_of_invention updated successfully, and an Updated Approval request has been sent to $responsibleAttorney->name!");

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

        $approval->update([
            'status' => $request->input('status'),
            'remarks' => $request->input('remarks'),
            'submitted_at' => now()
        ]);

        $approval->refresh();

        $matterRequest = $approval->matter_request;

        $matterRequest->load([
            'conflict_user',
            'conductor',
            'responsible_attorney', 'partner',
            'docketing_user'
        ]);

        // Handle different statuses
        switch ($approval->status) {
            case MatterRequestApproval::STATUS_APPROVED:
                // Determine the next approver based on the current approval type
                switch ($approval->approval_type) {
                    case MatterRequestApproval::TYPE_RESPONSIBLE_ATTORNEY:
                        if (!$matterRequest->conflict_user_id){
                            return Redirect::back()->withInput()->with('error', 'Kindly ensure a conflict user is attached to the Matter Request before proceeding');
                        }
                        // Route to Conflicts Team
                        $new_approval = MatterRequestApproval::create([
                            'matter_request_id' => $matterRequest->id,
                            'user_id' => $matterRequest->conflict_user_id,
                            'approval_type' => MatterRequestApproval::TYPE_CONFLICTS_TEAM,
                            'status' => MatterRequestApproval::STATUS_PENDING,
                        ]);

                        $new_approval->load('matter_request');

                        $conflictUser = $matterRequest->conflict_user;

                        Notification::send($conflictUser, new MatterRequestStatusUpdateNotification($new_approval));

                        break;

                    case MatterRequestApproval::TYPE_CONFLICTS_TEAM:
                        // Route back to Responsible Attorney for final approval
                        $new_approval = MatterRequestApproval::create([
                            'matter_request_id' => $matterRequest->id,
                            'user_id' => $matterRequest->responsible_attorney_id,
                            'approval_type' => MatterRequestApproval::TYPE_RESPONSIBLE_ATTORNEY_FINAL,
                            'status' => MatterRequestApproval::STATUS_PENDING,
                        ]);

                        $new_approval->load('matter_request');

                        $responsibleAttorney = $matterRequest->responsible_attorney;
                        Notification::send($responsibleAttorney, new MatterRequestStatusUpdateNotification($new_approval));

                        break;

                    case MatterRequestApproval::TYPE_RESPONSIBLE_ATTORNEY_FINAL:
                        // Route to Secondary Partner
                        $new_approval = MatterRequestApproval::create([
                            'matter_request_id' => $matterRequest->id,
                            'user_id' => $matterRequest->partner_id,
                            'approval_type' => MatterRequestApproval::TYPE_SECONDARY_PARTNER,
                            'status' => MatterRequestApproval::STATUS_PENDING,
                        ]);

                        $new_approval->load('matter_request');
                        $partner = $matterRequest->partner;
                        Notification::send($partner, new MatterRequestStatusUpdateNotification($new_approval));

                        break;

                    case MatterRequestApproval::TYPE_SECONDARY_PARTNER:
                        // Route to Docketing Team
                        $new_approval = MatterRequestApproval::create([
                            'matter_request_id' => $matterRequest->id,
                            'user_id' => $matterRequest->docketing_user_id,
                            'approval_type' => MatterRequestApproval::TYPE_DOCKETING_TEAM,
                            'status' => MatterRequestApproval::STATUS_PENDING,
                        ]);

                        $new_approval->load('matter_request');
                        $docketing_user = $matterRequest->docketing_user;
                        Notification::send($docketing_user, new MatterRequestStatusUpdateNotification($new_approval));

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

    /**
     * Matter Requests DataTable
     * @return JsonResponse
     */
    public function matterRequestsData(): JsonResponse
    {
        Gate::authorize('viewAny', MatterRequest::class);

        $query = MatterRequest::query()
            ->select('matter_requests.*', 'users.name as resp_attorney')
            ->leftJoin('users', 'users.id','=','matter_requests.responsible_attorney_id')
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
            ->select('matter_requests.*', 'users.name as resp_attorney')
            ->leftJoin('users', 'users.id','=','matter_requests.responsible_attorney_id')
            ->whereHas('matter_request_approvals', function ($q){
                $q->where('user_id', '=', auth()->id());
                $q->where('status', '=', MatterRequestApproval::STATUS_PENDING);
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
            ->addColumn('resp_attorney_name', function ($query) {
                if ($query->responsible_attorney){
                    return $query->responsible_attorney->name;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('status', function ($query) {
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
                } else {
                    return '<div class="badge bg-warning rounded-pill">Pending</div>';
                }
            })
            ->addColumn('action', function ($query) {
                return '
                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('matter-requests.show', $query).'"><i class="fa-regular fa-eye"></i></a>
                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('matter-requests.edit', $query).'"><i class="fa-regular fa-edit"></i></a>
                       ';
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
                if (auth()->id() === $query->user_id and $query->status === MatterRequestApproval::STATUS_PENDING) {
                    return '<button type="button" class="btn btn-sm btn-primary create-approval" data-approval_id="'.$query->id.'" data-bs-toggle="modal" data-bs-target="#createApprovalModal">
                            Create Approval
                        </button>';
                }
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
}
