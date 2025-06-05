<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $wishlists = [
            [ 'member_id' => 1, 'book_id' => 1 ],
            [ 'member_id' => 2, 'book_id' => 2 ],
            [ 'member_id' => 3, 'book_id' => 3 ],
            [ 'member_id' => 4, 'book_id' => 4 ],
            [ 'member_id' => 5, 'book_id' => 5 ],
        ];
        foreach ($wishlists as $wishlist) {
            \App\Models\Wishlist::create($wishlist);
        }
    }
}
