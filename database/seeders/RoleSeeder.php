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

                'picklists.picklists.browse',
                'picklists.picklists.read',
                'picklists.picklists.edit',
                'picklists.picklists.add',
                'picklists.picklists.delete',

                'picklists.picklist-items.browse',
                'picklists.picklist-items.read',
                'picklists.picklist-items.edit',
                'picklists.picklist-items.add',
                'picklists.picklist-items.delete',

                'matters.requests.browse',
                'matters.requests.read',
                'matters.requests.edit',
                'matters.requests.add',
                'matters.requests.delete',
            ],
            'responsible_attorney' => [
                //picklists module
                'picklists.picklists.browse',
                'picklists.picklists.read',
                'picklists.picklists.edit',
                'picklists.picklists.add',
                'picklists.picklists.delete',

                'picklists.picklist-items.browse',
                'picklists.picklist-items.read',
                'picklists.picklist-items.edit',
                'picklists.picklist-items.add',
                'picklists.picklist-items.delete',

                //matter requests module
                'matters.requests.browse',
                'matters.requests.read',
                'matters.requests.edit',
                'matters.requests.add',
                'matters.requests.delete',
            ],
            'partner' => [
                //picklists module
                'picklists.picklists.browse',
                'picklists.picklists.read',
                'picklists.picklists.edit',
                'picklists.picklists.add',
                'picklists.picklists.delete',

                'picklists.picklist-items.browse',
                'picklists.picklist-items.read',
                'picklists.picklist-items.edit',
                'picklists.picklist-items.add',
                'picklists.picklist-items.delete',

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
        ];

        // Create roles and attach permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::updateOrCreate(['name' => $roleName], ['guard_name' => 'web']);
            $permissions = Permission::query()->whereIn('name', $rolePermissions)->get();
            $role->givePermissionTo($permissions);
        }
    }
}
