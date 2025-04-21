<?php

namespace Tests\Unit;

use App\Models\Review_Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Review_BukuTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_buku_factory_creates_review(): void
    {
        $review = \App\Models\Review_Buku::factory()->create();
        $this->assertInstanceOf(\App\Models\Review_Buku::class, $review);
    }
}
