<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'admin_id' => 1,
                'title' => 'Library Closed',
                'content' => 'The library will be closed this Friday.',
                'status' => 'published',
                'post_date' => now()->subDays(7),
            ],
            [
                'admin_id' => 1,
                'title' => 'New Books Arrived',
                'content' => 'Check out our new arrivals in the science section.',
                'status' => 'published',
                'post_date' => now()->subDays(5),
            ],
            [
                'admin_id' => 1,
                'title' => 'Reading Competition',
                'content' => 'Join our annual reading competition.',
                'status' => 'published',
                'post_date' => now()->subDays(3),
            ],
            [
                'admin_id' => 1,
                'title' => 'Workshop',
                'content' => 'Attend our bookbinding workshop next week.',
                'status' => 'published',
                'post_date' => now()->subDays(2),
            ],
            [
                'admin_id' => 1,
                'title' => 'Holiday Hours',
                'content' => 'The library will have special hours during the holidays.',
                'status' => 'published',
                'post_date' => now()->subDay(),
            ],
        ];
        foreach ($announcements as $a) {
            Pengumuman::create($a);
        }
    }
}
