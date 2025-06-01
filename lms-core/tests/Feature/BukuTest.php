<?php

namespace Tests\Unit;

use App\Models\Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuTest extends TestCase
{
    use RefreshDatabase;

    public function test_buku_factory_creates_buku(): void
    {
        $buku = \App\Models\Buku::factory()->create();
        $this->assertInstanceOf(\App\Models\Buku::class, $buku);
    }
}
#tes