<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('app.permissions');
        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
