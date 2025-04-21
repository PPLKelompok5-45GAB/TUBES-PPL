<?php

namespace Database\Seeders;

use App\Models\Log_Stock_Buku;
use Illuminate\Database\Seeder;

class LogStockBukuSeeder extends Seeder
{
    public function run(): void
    {
        Log_Stock_Buku::create([
            // 'log_id' => 1, // Let DB auto-increment
            'book_id' => 1,
            'entry_date' => now()->subDays(10),
            'qty_added' => 10,
            'qty_removed' => 0,
            'notes' => 'Initial stock',
        ]);
    }
}
