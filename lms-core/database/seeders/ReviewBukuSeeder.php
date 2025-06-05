<?php

namespace Database\Seeders;

use App\Models\Review_Buku;
use Illuminate\Database\Seeder;

class ReviewBukuSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            [
                'book_id' => 1,
                'member_id' => 1,
                'rating' => 4.5,
                'review_text' => 'Great book!',
                'review_date' => now(),
            ],
            [
                'book_id' => 2,
                'member_id' => 2,
                'rating' => 3.0,
                'review_text' => 'Informative.',
                'review_date' => now(),
            ],
            [
                'book_id' => 3,
                'member_id' => 3,
                'rating' => 5.0,
                'review_text' => 'Excellent resource!',
                'review_date' => now(),
            ],
            [
                'book_id' => 4,
                'member_id' => 4,
                'rating' => 4.0,
                'review_text' => 'Well written.',
                'review_date' => now(),
            ],
            [
                'book_id' => 5,
                'member_id' => 5,
                'rating' => 2.5,
                'review_text' => 'Could be better.',
                'review_date' => now(),
            ],
        ];
        foreach ($reviews as $review) {
            \App\Models\Review_Buku::create($review);
        }
    }
}
