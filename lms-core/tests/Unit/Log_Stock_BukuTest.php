<?php

namespace Tests\Unit;

use App\Models\Log_Stock_Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Log_Stock_BukuTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_stock_buku_factory_creates_log(): void
    {
        $log = Log_Stock_Buku::factory()->create();
        $this->assertInstanceOf(Log_Stock_Buku::class, $log);
    }
}
