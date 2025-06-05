<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_id' => 1, 'category_name' => 'Fiction'],
            ['category_id' => 2, 'category_name' => 'Non-Fiction'],
            ['category_id' => 3, 'category_name' => 'Science'],
            ['category_id' => 4, 'category_name' => 'Technology'],
            ['category_id' => 5, 'category_name' => 'History'],
        ];
        foreach ($categories as $cat) {
            \App\Models\Kategori::create($cat);
        }
    }
}
