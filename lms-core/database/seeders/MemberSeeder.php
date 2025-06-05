<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        // Sync members with users table
        $users = \App\Models\User::where('role', 'Member')->get();
        foreach ($users as $user) {
            \App\Models\Member::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->username ?? $user->name ?? $user->email,
                    'email' => $user->email,
                    'phone' => '',
                    'address' => '',
                    'membership_date' => now(),
                    'status' => 'active',
                ]
            );
        }

        $userMembers = [
            [
                'member_id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '1111111111',
                'address' => 'Address 1',
                'membership_date' => now(),
                'status' => 'active',
            ],
            [
                'member_id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '2222222222',
                'address' => 'Address 2',
                'membership_date' => now(),
                'status' => 'active',
            ],
        ];
        foreach ($userMembers as $member) {
            \App\Models\Member::updateOrCreate(
                ['member_id' => $member['member_id']],
                $member
            );
        }

        $members = [
            [
                'member_id' => 3,
                'name' => 'Member Three',
                'email' => 'member3@example.com',
                'phone' => '3333333333',
                'address' => 'Address 3',
                'membership_date' => now(),
                'status' => 'active',
            ],
            [
                'member_id' => 4,
                'name' => 'Member Four',
                'email' => 'member4@example.com',
                'phone' => '4444444444',
                'address' => 'Address 4',
                'membership_date' => now(),
                'status' => 'active',
            ],
            [
                'member_id' => 5,
                'name' => 'Member Five',
                'email' => 'member5@example.com',
                'phone' => '5555555555',
                'address' => 'Address 5',
                'membership_date' => now(),
                'status' => 'inactive',
            ],
        ];
        foreach ($members as $member) {
            \App\Models\Member::updateOrCreate(
                ['email' => $member['email']],
                $member
            );
        }
    }
}
