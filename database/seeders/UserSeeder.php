<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com', // Tambahkan ini
            'password' => Hash::make('password'),
        ]);
        

        User::factory()->count(5)->create(); // jika kamu ingin menambahkan dummy dari factory
    }
}

