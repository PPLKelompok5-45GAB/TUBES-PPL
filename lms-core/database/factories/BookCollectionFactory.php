<?php

namespace Database\Factories;

use App\Models\BookCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookCollectionFactory extends Factory
{
    protected $model = BookCollection::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'cover_image' => $this->faker->imageUrl(300, 400, 'books', true, 'Book'),
        ];
    }
}
