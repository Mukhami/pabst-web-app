<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatterTypeRequest;
use App\Http\Requests\UpdateMatterTypeRequest;
use App\Models\MatterType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class MatterTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', MatterType::class);

        return view('backend.matter-types.index', [
            'title' => 'Matter Type Management',
            'sub_title' => 'List of all matter types.',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', MatterType::class);

        return view('backend.matter-types.create', [
            'title' => 'Matter Type Management',
            'sub_title' => 'Add new Matter Type.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMatterTypeRequest $request): RedirectResponse
    {
        $key = $this->generateMatterTypeKey(Str::slug($request->input('name')));

        $matterType = MatterType::query()->create([
            'key' => $key,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->has('status'),
        ]);

        return Redirect::route('matter-types.index')->with('success', "$matterType->name created successfully!");

    }

    /**
     * Display the specified resource.
     */
    public function show(MatterType $matterType): View
    {
        Gate::authorize('view', MatterType::class);

        return view('backend.matter-types.show', [
            'title' => 'Matter Type Management',
            'sub_title' => 'Add new Matter Type.',
            'matterType' => $matterType
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MatterType $matterType): View
    {
        Gate::authorize('update', $matterType);

        return view('backend.matter-types.edit', [
            'title' => 'Matter Type Management',
            'sub_title' => 'Edit Matter Type Details.',
            'matterType' => $matterType
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMatterTypeRequest $request, MatterType $matterType): RedirectResponse
    {

        $key = $this->generateMatterTypeKey(Str::slug($request->input('name')));

        $matterType->update([
            'key' => $key,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->has('status'),
        ]);

        return Redirect::route('matter-types.index')->with('success', "$matterType->name updated successfully!");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatterType $matterType): RedirectResponse
    {
        Gate::authorize('delete', $matterType);

        $matterType->delete();

        $matterType->matter_sub_types()->delete();

        return Redirect::route('matter-types.index')->with('success', "$matterType->name deleted successfully!");
    }

    private function generateMatterTypeKey($key): string
    {
        if (MatterType::query()->where('key', '=', $key)->exists()) {
            $key = $key . '-' . mt_rand(100, 999);
            $this->generateMatterTypeKey($key);
        }
        return $key;
    }

    /**
     * Matter Type DataTable
     * @return JsonResponse
     */
    public function matterTypesData(): JsonResponse
    {
        Gate::authorize('viewAny', MatterType::class);

        $query = MatterType::query()->withCount('matter_sub_types');
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
                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('matter-types.show', $query).'"><i class="fa-regular fa-eye"></i></a>
                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('matter-types.edit', $query).'"><i class="fa-regular fa-edit"></i></a>
                        <button class="btn btn-datatable btn-icon btn-transparent-dark delete-record-btn" data-route="'.route('matter-types.destroy', $query).'"
                        onclick="if(confirm(`Are you sure you want to delete this record?`)) {
                           document.getElementById(`delete-matter-types'.$query->id.'`).submit()
                        }">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                        <form action="'.route('matter-types.destroy', $query).'" method="POST" id="delete-matter-types'.$query->id.'" style="display: none;">
                            '.csrf_field().'
                            '.method_field('delete').'
                        </form>
                        ';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
}
