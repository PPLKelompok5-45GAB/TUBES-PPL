<?php

namespace Database\Factories;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    public function definition(): array
    {
        return [
            'member_id' => \App\Models\Member::factory(),
            'book_id' => \App\Models\Buku::factory(),
        ];
    }
}
