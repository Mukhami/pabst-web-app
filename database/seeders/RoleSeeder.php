<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin' => [
                'users.users.browse',
                'users.users.read',
                'users.users.edit',
                'users.users.add',
                'users.users.delete',

                'users.roles.browse',
                'users.roles.read',
                'users.roles.edit',
                'users.roles.add',
                'users.roles.delete',

                'matter-types.matter-types.browse',
                'matter-types.matter-types.read',
                'matter-types.matter-types.edit',
                'matter-types.matter-types.add',
                'matter-types.matter-types.delete',

                'matter-types.matter-subtypes.browse',
                'matter-types.matter-subtypes.read',
                'matter-types.matter-subtypes.edit',
                'matter-types.matter-subtypes.add',
                'matter-types.matter-subtypes.delete',

                'matters.requests.browse',
                'matters.requests.read',
                'matters.requests.edit',
                'matters.requests.add',
                'matters.requests.delete',
            ],
            'responsible_attorney' => [
                'matter-types.matter-types.browse',
                'matter-types.matter-types.read',
                'matter-types.matter-types.edit',
                'matter-types.matter-types.add',
                'matter-types.matter-types.delete',

                'matter-types.matter-subtypes.browse',
                'matter-types.matter-subtypes.read',
                'matter-types.matter-subtypes.edit',
                'matter-types.matter-subtypes.add',
                'matter-types.matter-subtypes.delete',

                //matter requests module
                'matters.requests.browse',
                'matters.requests.read',
                'matters.requests.edit',
                'matters.requests.add',
                'matters.requests.delete',
            ],
            'partner' => [
                'matter-types.matter-types.browse',
                'matter-types.matter-types.read',
                'matter-types.matter-types.edit',
                'matter-types.matter-types.add',
                'matter-types.matter-types.delete',

                'matter-types.matter-subtypes.browse',
                'matter-types.matter-subtypes.read',
                'matter-types.matter-subtypes.edit',
                'matter-types.matter-subtypes.add',
                'matter-types.matter-subtypes.delete',

                //matter requests module
                'matters.requests.browse',
                'matters.requests.read',
                'matters.requests.edit',
                'matters.requests.add',
                'matters.requests.delete',
            ],
            'conflict' => [
                //matter requests module
                'matters.requests.browse',
                'matters.requests.read',
                'matters.requests.edit',
                'matters.requests.add',
                'matters.requests.delete',
            ],
            'general' => [
                //matter requests module
                'matters.requests.browse',
                'matters.requests.read',
                'matters.requests.edit',
                'matters.requests.add',
                'matters.requests.delete',
            ],
        ];

        // Create roles and attach permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::updateOrCreate(['name' => $roleName], ['guard_name' => 'web']);
            $permissions = Permission::query()->whereIn('name', $rolePermissions)->get();
            $role->givePermissionTo($permissions);
        }
    }
}
