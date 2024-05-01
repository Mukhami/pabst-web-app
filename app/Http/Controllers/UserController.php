<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('backend.users.index', [
            'title' => 'User Management',
            'sub_title' => 'List of all system users.',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * User DataTable
     * @return JsonResponse
     */
    public function usersData(): JsonResponse
    {
        $query = User::query()->with('roles');
        return DataTables::eloquent($query)
            ->addColumn('user_roles', function ($query) {
                return $query->roles->map(function ($role) {
                    return '<div class="badge bg-dark rounded-pill">' . ucwords(str_replace('_', ' ', $role->name)) . '</div>';
                })->implode(' ');
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
                        <button class="btn btn-datatable btn-icon btn-transparent-dark me-2"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        <button class="btn btn-datatable btn-icon btn-transparent-dark"><i class="fa-regular fa-trash-can"></i></button>
                        ';
            })
            ->rawColumns(['status', 'user_roles', 'action'])
            ->toJson();
    }
}
