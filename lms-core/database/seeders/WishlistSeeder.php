<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        Wishlist::create([
            'book_id' => 1,
            'member_id' => 1,
            'added_date' => now(),
        ]);
    }
}
