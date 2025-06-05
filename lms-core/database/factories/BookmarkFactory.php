<?php

namespace Database\Factories;

use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookmarkFactory extends Factory
{
    protected $model = Bookmark::class;

    public function definition(): array
    {
        return [
            'member_id' => \App\Models\Member::factory(),
            'book_id' => \App\Models\Buku::factory(),
            'page_number' => $this->faker->numberBetween(1, 500),
            'notes' => $this->faker->optional(0.7)->paragraph(1),
            'added_date' => $this->faker->date(),
        ];
    }
}
