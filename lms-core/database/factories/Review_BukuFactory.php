<?php

namespace Database\Factories;

use App\Models\Review_Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class Review_BukuFactory extends Factory
{
    protected $model = Review_Buku::class;

    public function definition(): array
    {
        return [
            'member_id' => \App\Models\Member::factory(),
            'book_id' => \App\Models\Buku::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'review_text' => $this->faker->sentence(),
            'review_date' => $this->faker->date(),
        ];
    }
}
