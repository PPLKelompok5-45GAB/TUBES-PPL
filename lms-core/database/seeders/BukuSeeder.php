<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        Buku::create([
            'book_id' => 1,
            'category_id' => 1,
            'title' => 'Sample Book',
            'author' => 'Jane Smith',
            'isbn' => '9781234567890',
            'publication_year' => 2022,
            'publisher' => 'Sample Publisher',
            'total_stock' => 10,
            'borrowed_qty' => 2,
            'available_qty' => 8,
        ]);
    }
}
