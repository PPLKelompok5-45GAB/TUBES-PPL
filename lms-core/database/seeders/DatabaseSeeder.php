<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\AdminSeeder;
use Database\Seeders\BookmarkSeeder;
use Database\Seeders\BukuSeeder;
use Database\Seeders\KategoriSeeder;
use Database\Seeders\LogPinjamBukuSeeder;
use Database\Seeders\LogStockBukuSeeder;
use Database\Seeders\MemberSeeder;
use Database\Seeders\PengumumanSeeder;
use Database\Seeders\ReviewBukuSeeder;
use Database\Seeders\WishlistSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Truncate tables to avoid foreign key conflicts and duplicate data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('buku')->truncate();
        DB::table('member')->truncate();
        DB::table('bookmarks')->truncate();
        DB::table('kategori')->truncate();
        DB::table('admin')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([
            [
                'username' => 'admin',
                'firstname' => 'Admin',
                'lastname' => 'Admin',
                'email' => 'admin@argon.com',
                'password' => bcrypt('secret'),
                'role' => 'Admin',
            ],
            [
                'username' => 'user1',
                'firstname' => 'John',
                'lastname' => 'Doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password1'),
                'role' => 'Member',
            ],
            [
                'username' => 'user2',
                'firstname' => 'Jane',
                'lastname' => 'Smith',
                'email' => 'jane@example.com',
                'password' => bcrypt('password2'),
                'role' => 'Member',
            ],
        ]);

        $this->call([
            KategoriSeeder::class, // Ensure categories exist first
            BukuSeeder::class, // Ensure books exist after categories
            MemberSeeder::class, // Ensure members exist before bookmarks
            BookmarkSeeder::class, // Now safe to insert bookmarks
            AdminSeeder::class,
            LogPinjamBukuSeeder::class,
            LogStockBukuSeeder::class,
            PengumumanSeeder::class,
            ReviewBukuSeeder::class,
            WishlistSeeder::class,
        ]);
    }
}
