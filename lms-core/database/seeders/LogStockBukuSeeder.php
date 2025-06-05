<?php

namespace Database\Seeders;

use App\Models\Log_Stock_Buku;
use Illuminate\Database\Seeder;

class LogStockBukuSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            [
                'book_id' => 1,
                'entry_date' => now()->subDays(30),
                'qty_added' => 5,
                'qty_removed' => 0,
                'notes' => 'Initial stock',
            ],
            [
                'book_id' => 2,
                'entry_date' => now()->subDays(20),
                'qty_added' => 3,
                'qty_removed' => 0,
                'notes' => 'Restock',
            ],
            [
                'book_id' => 3,
                'entry_date' => now()->subDays(15),
                'qty_added' => 0,
                'qty_removed' => 1,
                'notes' => 'Damaged',
            ],
            [
                'book_id' => 4,
                'entry_date' => now()->subDays(10),
                'qty_added' => 2,
                'qty_removed' => 0,
                'notes' => 'Donation',
            ],
            [
                'book_id' => 5,
                'entry_date' => now()->subDays(5),
                'qty_added' => 0,
                'qty_removed' => 2,
                'notes' => 'Lost',
            ],
        ];
        foreach ($logs as $log) {
            \App\Models\Log_Stock_Buku::create($log);
        }
    }
}
