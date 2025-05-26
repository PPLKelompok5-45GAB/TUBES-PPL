<?php

namespace Tests\Unit;

use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriTest extends TestCase
{
    use RefreshDatabase;

    public function test_kategori_factory_creates_kategori(): void
    {
        $kategori = Kategori::factory()->create();
        $this->assertInstanceOf(Kategori::class, $kategori);
    }
}
