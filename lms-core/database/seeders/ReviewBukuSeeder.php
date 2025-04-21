<?php

namespace Database\Seeders;

use App\Models\Review_Buku;
use Illuminate\Database\Seeder;

class ReviewBukuSeeder extends Seeder
{
    public function run(): void
    {
        Review_Buku::create([
            'book_id' => 1,
            'member_id' => 1,
            'rating' => 4.5,
            'review_text' => 'Great book!',
            'review_date' => now(),
        ]);
    }
}
