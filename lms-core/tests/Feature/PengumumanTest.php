<?php

namespace Tests\Unit;

use App\Models\Pengumuman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengumumanTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengumuman_factory_creates_pengumuman(): void
    {
        $pengumuman = Pengumuman::factory()->create();
        $this->assertInstanceOf(Pengumuman::class, $pengumuman);
    }
}
#halo