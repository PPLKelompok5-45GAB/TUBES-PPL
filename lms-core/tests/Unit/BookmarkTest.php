<?php

namespace Tests\Unit;

use App\Models\Bookmark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_bookmark_factory_creates_bookmark(): void
    {
        $bookmark = Bookmark::factory()->create();
        $this->assertInstanceOf(Bookmark::class, $bookmark);
    }
}
