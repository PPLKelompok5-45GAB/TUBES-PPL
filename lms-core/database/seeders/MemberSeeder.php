<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        Member::create([
            'member_id' => 1,
            'name' => 'Member',
            'email' => 'member@example.com',
            'phone' => '123456789',
            'address' => 'Member Address',
            'membership_date' => now(),
            'status' => 'active',
        ]);
    }
}
