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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MatterRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreMatterRequestRequest $request)
    {
        $validatedData = $request->validated();
        $matterRequest = new MatterRequest();
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

        $responsibleAttorney->notify(new NewMatterRequestAssignment($matterRequest));


        MatterRequestApproval::query()->create([
            'matter_request_id' => $matterRequest->id,
            'user_id' => $responsibleAttorney->id,
            'status' => MatterRequestApproval::STATUS_PENDING,
        ]);


        return Redirect::route('matter-requests.index')->with('success', "$matterRequest->title_of_invention created successfully!");
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
    public function edit(MatterRequest $matterRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMatterRequestRequest $request, MatterRequest $matterRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatterRequest $matterRequest)
    {
        //
    }
}
