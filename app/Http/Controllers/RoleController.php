<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('backend.roles.index', [
            'title' => 'Role Management',
            'sub_title' => 'List of all system roles.',
        ]);
    }


    /**
     * Roles DataTable
     * @return JsonResponse
     */
    public function rolesData(): JsonResponse
    {
        $query = Role::query();
        return DataTables::eloquent($query)
            ->editColumn('name', function (Role $role) {
                return ucwords(str_replace('_', ' ', $role->name));
            })
            ->editColumn('created_at', function (Role $role) {
                return $role->created_at->format('Y-m-d h:i:s');
            })
            ->editColumn('updated_at', function (Role $role) {
                return $role->updated_at->format('Y-m-d h:i:s');
            })
            ->toJson();
    }
}
