<?php

namespace Database\Seeders;

use App\Models\Log_Pinjam_Buku;
use Illuminate\Database\Seeder;

class LogPinjamBukuSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            [
                'book_id' => 1,
                'member_id' => 1,
                'borrow_date' => now()->subDays(10),
                'due_date' => now()->subDays(3),
                'return_date' => now()->subDays(5),
                'status' => 'returned',
            ],
            [
                'book_id' => 2,
                'member_id' => 2,
                'borrow_date' => now()->subDays(8),
                'due_date' => now()->addDays(2),
                'return_date' => now()->subDays(3),
                'status' => 'returned',
            ],
            [
                'book_id' => 3,
                'member_id' => 3,
                'borrow_date' => now()->subDays(6),
                'due_date' => now()->addDays(4),
                'return_date' => null,
                'status' => 'approved',
            ],
            [
                'book_id' => 4,
                'member_id' => 4,
                'borrow_date' => now()->subDays(4),
                'due_date' => now()->addDays(6),
                'return_date' => null,
                'status' => 'approved',
            ],
            [
                'book_id' => 5,
                'member_id' => 5,
                'borrow_date' => now()->subDays(2),
                'due_date' => now()->addDays(8),
                'return_date' => null,
                'status' => 'approved',
            ],
        ];
        foreach ($logs as $log) {
            Log_Pinjam_Buku::create($log);
        }
    }
}
