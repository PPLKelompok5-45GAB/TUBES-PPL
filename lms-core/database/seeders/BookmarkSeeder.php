<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    public function run(): void
    {
        Bookmark::create([
            'book_id' => 1,
            'member_id' => 1,
            'page_number' => 5,
            'notes' => 'Interesting section',
            'added_date' => now(),
        ]);
    }
}
