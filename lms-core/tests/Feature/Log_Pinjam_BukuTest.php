<?php

namespace Tests\Unit;

use App\Models\Log_Pinjam_Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Log_Pinjam_BukuTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_pinjam_buku_factory_creates_log(): void
    {
        $log = Log_Pinjam_Buku::factory()->create();
        $this->assertInstanceOf(Log_Pinjam_Buku::class, $log);
    }
}
