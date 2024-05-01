<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@mkenga.com',
                'email_verified_at' => now(),
                'password' => bcrypt('test1234'),
                'role' => 'admin'
            ],
            [
                'name' => 'Resp. Attorney',
                'email' => 'resp@mkenga.com',
                'email_verified_at' => now(),
                'password' => bcrypt('test1234'),
                'role' => 'responsible_attorney'
            ],
            [
                'name' => 'Partner',
                'email' => 'partner@mkenga.com',
                'email_verified_at' => now(),
                'password' => bcrypt('test1234'),
                'role' => 'partner'
            ],
            [
                'name' => 'Conflict',
                'email' => 'conflict@mkenga.com',
                'email_verified_at' => now(),
                'password' => bcrypt('test1234'),
                'role' => 'conflict'
            ],
        ];

        foreach($users as $systemUser) {
            $user = User::updateOrCreate(['email' => $systemUser['email']],
                [
                'name' => $systemUser['name'],
                'email' => $systemUser['email'],
                'email_verified_at' => now(),
                'password' => $systemUser['password'],
                ]
            );
            $user->assignRole($systemUser['role']);
        }
    }
}
