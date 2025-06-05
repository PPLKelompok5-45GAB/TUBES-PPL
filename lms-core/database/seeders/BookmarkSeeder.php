<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    public function run(): void
    {
        $bookmarks = [
            [
                'book_id' => 1,
                'member_id' => 1,
                'page_number' => 5,
                'notes' => 'Interesting section',
                'added_date' => now(),
            ],
            [
                'book_id' => 2,
                'member_id' => 2,
                'page_number' => 10,
                'notes' => 'Favorite chapter',
                'added_date' => now(),
            ],
            [
                'book_id' => 3,
                'member_id' => 3,
                'page_number' => 15,
                'notes' => 'Review later',
                'added_date' => now(),
            ],
            [
                'book_id' => 4,
                'member_id' => 4,
                'page_number' => 20,
                'notes' => 'Important formula',
                'added_date' => now(),
            ],
            [
                'book_id' => 5,
                'member_id' => 5,
                'page_number' => 25,
                'notes' => 'Summary',
                'added_date' => now(),
            ],
        ];
        foreach ($bookmarks as $bookmark) {
            \App\Models\Bookmark::create($bookmark);
        }
    }
}
