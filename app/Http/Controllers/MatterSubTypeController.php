<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatterSubTypeRequest;
use App\Http\Requests\UpdateMatterSubTypeRequest;
use App\Models\MatterSubType;
use App\Models\MatterType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class MatterSubTypeController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMatterSubTypeRequest $request): RedirectResponse
    {
        $matterType = MatterType::query()->find($request->input('matter_type_id'));

        $key = $this->generateMatterTypeKey(Str::slug($request->input('name')));

        $matterSubType = $matterType->matter_sub_types()->create([
            'key' => $key,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->has('status'),
        ]);

        return Redirect::route('matter-types.show', $matterType)->with('success', "$matterSubType->name created successfully!");

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MatterSubType $matterSubType): View
    {
        Gate::authorize('update', MatterSubType::class);

        $matterSubType->load('matter_type');

        return view('backend.matter-types.edit-sub-type', [
            'title' => 'Matter Type Management',
            'sub_title' => 'Edit Sub Type Details.',
            'matterSubType' => $matterSubType
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMatterSubTypeRequest $request, MatterSubType $matterSubType): RedirectResponse
    {
        $key = $this->generateMatterTypeKey(Str::slug($request->input('name')));

        $matterSubType->update([
            'key' => $key,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->has('status'),
        ]);

        $matterSubType->load('matter_type');

        $matterType = $matterSubType->matter_type;

        return Redirect::route('matter-types.show', $matterType)->with('success', "$matterSubType->name updated successfully!");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatterSubType $matterSubType): RedirectResponse
    {
        Gate::authorize('delete', MatterType::class);


        $matterSubType->load('matter_type');

        $matterType = $matterSubType->matter_type;

        $matterSubType->delete();

        return Redirect::route('matter-types.show', $matterType)->with('success', "$matterSubType->name deleted successfully!");

    }

    private function generateMatterTypeKey($key): string
    {
        if (MatterSubType::query()->where('key', '=', $key)->exists()) {
            $key = $key . '-' . mt_rand(100, 999);
            $this->generateMatterTypeKey($key);
        }
        return $key;
    }

    /**
     * Matter Sub Type DataTable
     * @param MatterType $matterType
     * @return JsonResponse
     */
    public function matterSubTypesData(MatterType $matterType): JsonResponse
    {
        Gate::authorize('viewAny', MatterSubType::class);

        $query = MatterSubType::query()->where('matter_type_id', '=', $matterType->id);
        return DataTables::eloquent($query)
            ->editColumn('description', function ($query) {
                return Str::limit($query->description, 40);
            })
            ->editColumn('status', function ($query) {
                if ($query->status){
                    return '<div class="badge bg-success rounded-pill">Active</div>';
                } else {
                    return '<div class="badge bg-danger rounded-pill">Inactive</div>';
                }
            })
            ->addColumn('action', function ($query) {
                return '
                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2"
                        href="'.route('matter-sub-types.edit', $query).'"><i class="fa-regular fa-edit"></i></a>
                        <button class="btn btn-datatable btn-icon btn-transparent-dark delete-record-btn" data-route="'.route('matter-sub-types.destroy', $query).'"
                        onclick="if(confirm(`Are you sure you want to delete this record?`)) {
                           document.getElementById(`delete-matter-types'.$query->id.'`).submit()
                        }">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                        <form action="'.route('matter-sub-types.destroy', $query).'" method="POST" id="delete-matter-types'.$query->id.'" style="display: none;">
                            '.csrf_field().'
                            '.method_field('delete').'
                        </form>
                        ';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
}
