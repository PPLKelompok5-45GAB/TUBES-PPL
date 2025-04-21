<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        Pengumuman::create([
            'admin_id' => 1,
            'title' => 'Test Announcement',
            'content' => 'This is a test announcement.',
            'status' => 'published',
            'post_date' => now(),
        ]);
    }
}
