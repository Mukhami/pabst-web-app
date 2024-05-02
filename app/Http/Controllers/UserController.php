<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Notifications\NewUserCredentials;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
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
    public function create(): View
    {
        Gate::authorize('create', User::class);

        $roles = Role::all();

        return view('backend.users.create', [
            'title' => 'User Management',
            'sub_title' => 'Create a new System User.',
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $password = "Pabst@".mt_rand(10000, 99999);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($password),
            'status' => $request->has('status'),
        ]);

        $roles = Role::query()->whereIn('id', $request->input('roles'))->get();

        $user->assignRole($roles);

        $user->notify(new NewUserCredentials($password));

        return Redirect::route('users.index')->with('success', 'User created successfully!');

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
    public function edit(User $user): View
    {
        Gate::authorize('update', User::class);

        $roles = Role::all();

        return view('backend.users.edit', [
            'title' => 'User Management',
            'sub_title' => "Edit $user->name's details.",
            'roles' => $roles,
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'status' => $request->has('status'),
        ]);

        $user = $user->refresh();

        $roles = Role::query()->whereIn('id', $request->input('roles'))->get();

        $user->syncRoles($roles);

        return Redirect::route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', User::class);

        $authUser = auth()->user();
        if ($authUser->id === $user->id) {
            return Redirect::route('users.index')->with('error', 'You are not permitted to delete your own account.');
        } else {
            $user->delete();
            return Redirect::route('users.index')->with('success', 'User deleted successfully!');
        }
    }

    /**
     * User DataTable
     * @return JsonResponse
     */
    public function usersData(): JsonResponse
    {
        Gate::authorize('viewAny', User::class);

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
                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('users.edit', $query).'"><i class="fa-regular fa-edit"></i></a>
                        <button class="btn btn-datatable btn-icon btn-transparent-dark delete-record-btn" data-route="'.route('users.destroy', $query).'"
                        onclick="if(confirm(`Are you sure you want to delete this record?`)) {
                           document.getElementById(`deleteUser'.$query->id.'`).submit()
                        }">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                        <form action="'.route('users.destroy', $query).'" method="POST" id="deleteUser'.$query->id.'" style="display: none;">
                            '.csrf_field().'
                            '.method_field('delete').'
                        </form>
                        ';
            })
            ->rawColumns(['status', 'user_roles', 'action'])
            ->toJson();
    }
}
