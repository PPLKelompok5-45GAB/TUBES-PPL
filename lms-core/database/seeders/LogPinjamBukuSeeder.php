<?php

namespace Database\Seeders;

use App\Models\Log_Pinjam_Buku;
use Illuminate\Database\Seeder;

class LogPinjamBukuSeeder extends Seeder
{
    public function run(): void
    {
        Log_Pinjam_Buku::create([
            'book_id' => 1,
            'member_id' => 1,
            'borrow_date' => now()->subDays(3),
            'due_date' => now()->addDays(7),
            'return_date' => null,
            'status' => 'borrowed',
        ]);
    }
}
