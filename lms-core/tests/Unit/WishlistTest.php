<?php

namespace Tests\Unit;

use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_wishlist_factory_creates_wishlist(): void
    {
        $wishlist = Wishlist::factory()->create();
        $this->assertInstanceOf(Wishlist::class, $wishlist);
    }
}
