<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatterRequestRequest;
use App\Http\Requests\UpdateMatterRequestRequest;
use App\Models\MatterRequest;
use App\Models\MatterRequestApproval;
use App\Models\MatterSubType;
use App\Models\MatterType;
use App\Models\User;
use App\Notifications\NewMatterRequestAssignment;
use App\Notifications\UpdatedMatterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
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
    public function show(MatterRequest $matterRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MatterRequest $matterRequest): View
    {
        Gate::authorize('update', MatterRequest::class);

        $matter_types = MatterType::query()->where('status', '=', true)->get();
        $matter_sub_types = MatterSubType::query()->where('status', '=', true)->get();
        $responsible_attorneys = User::role('responsible_attorney')->where('status', '=', true)->get();
        $staff = User::role('general')->where('status', '=', true)->get();
        $partners = User::role('partner')->where('status', '=', true)->get();

        return view('backend.matter-requests.edit', [
            'title' => 'Matter Request Management',
            'sub_title' => 'Update Matter Request Details.',
            'matterRequest' => $matterRequest,
            'matter_types' => $matter_types,
            'matter_sub_types' => $matter_sub_types,
            'responsible_attorneys' => $responsible_attorneys,
            'staff' => $staff,
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
                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('matter-requests.index', $query).'"><i class="fa-regular fa-eye"></i></a>
                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('matter-requests.edit', $query).'"><i class="fa-regular fa-edit"></i></a>
                       ';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
}
