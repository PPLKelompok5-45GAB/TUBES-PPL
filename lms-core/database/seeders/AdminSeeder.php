<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Sync admins with users table
        $users = \App\Models\User::where('role', 'Admin')->get();
        foreach ($users as $user) {
            \App\Models\Admin::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->username ?? $user->name ?? $user->email,
                    'email' => $user->email,
                    'phone' => '',
                    'address' => '',
                    'status' => 'active',
                ]
            );
        }
        // Optionally keep old admins if needed
        // foreach ($admins as $admin) {
        //     \App\Models\Admin::updateOrCreate([
        //         'email' => $admin['email'],
        //     ], $admin);
        // }
    }
}
