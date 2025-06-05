<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
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
                'synopsis' => 'A sample synopsis for Sample Book.',
            ],
            [
                'book_id' => 2,
                'category_id' => 2,
                'title' => 'Another Book',
                'author' => 'John Doe',
                'isbn' => '9781234567891',
                'publication_year' => 2021,
                'publisher' => 'Another Publisher',
                'total_stock' => 5,
                'borrowed_qty' => 1,
                'available_qty' => 4,
                'synopsis' => 'A sample synopsis for Another Book.',
            ],
            [
                'book_id' => 3,
                'category_id' => 1,
                'title' => 'Learning Laravel',
                'author' => 'Lara Dev',
                'isbn' => '9781234567892',
                'publication_year' => 2020,
                'publisher' => 'Dev Books',
                'total_stock' => 8,
                'borrowed_qty' => 3,
                'available_qty' => 5,
                'synopsis' => 'A sample synopsis for Learning Laravel.',
            ],
            [
                'book_id' => 4,
                'category_id' => 3,
                'title' => 'PHP for Beginners',
                'author' => 'PHP Guru',
                'isbn' => '9781234567893',
                'publication_year' => 2019,
                'publisher' => 'PHP World',
                'total_stock' => 12,
                'borrowed_qty' => 4,
                'available_qty' => 8,
                'synopsis' => 'A sample synopsis for PHP for Beginners.',
            ],
            [
                'book_id' => 5,
                'category_id' => 2,
                'title' => 'Advanced PHP',
                'author' => 'Expert Coder',
                'isbn' => '9781234567894',
                'publication_year' => 2023,
                'publisher' => 'Expert Press',
                'total_stock' => 7,
                'borrowed_qty' => 0,
                'available_qty' => 7,
                'synopsis' => 'A sample synopsis for Advanced PHP.',
            ],
        ];
        foreach ($books as $book) {
            \App\Models\Buku::create($book);
        }
    }
}
