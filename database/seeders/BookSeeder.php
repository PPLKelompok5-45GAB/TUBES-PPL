<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'title' => 'Laravel untuk Pemula',
            'author' => 'Tegar A.',
            'category' => 'Pemrograman',
            'year' => 2023,
            'summary' => 'Panduan dasar menggunakan Laravel untuk pengembangan web modern.'
        ]);

        Book::create([
            'title' => 'Desain UI dengan Bootstrap',
            'author' => 'Siti K.',
            'category' => 'Desain',
            'year' => 2022,
            'summary' => 'Membahas pembuatan antarmuka responsif menggunakan Bootstrap.'
        ]);

        Book::factory()->count(10)->create(); // jika kamu ingin tambah dummy dari factory juga
    }
}
