<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Skip if not using SQLite
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('log_pinjam_buku', function (Blueprint $table) {
                $table->date('due_date')->nullable()->change();
            });
            return;
        }

        // For SQLite, we need to recreate the table
        Schema::rename('log_pinjam_buku', 'log_pinjam_buku_old');
        
        // Recreate the table with the correct schema
        Schema::create('log_pinjam_buku', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('member_id')->constrained('member', 'member_id');
            $table->foreignId('book_id')->constrained('buku', 'book_id');
            $table->date('borrow_date');
            $table->date('due_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('status', 20);
            $table->timestamps();
        });
        
        // Copy data from old table to new table
        DB::statement('INSERT INTO log_pinjam_buku (log_id, member_id, book_id, borrow_date, due_date, return_date, status, created_at, updated_at) SELECT loan_id, member_id, book_id, borrow_date, due_date, return_date, status, created_at, updated_at FROM log_pinjam_buku_old');
        
        // Drop the old table
        Schema::drop('log_pinjam_buku_old');
    }

    public function down()
    {
        // Skip if not using SQLite
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('log_pinjam_buku', function (Blueprint $table) {
                $table->date('due_date')->nullable(false)->change();
            });
            return;
        }

        // For SQLite, we need to recreate the table
        Schema::rename('log_pinjam_buku', 'log_pinjam_buku_old');
        
        // Recreate the table with the original schema
        Schema::create('log_pinjam_buku', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('member_id')->constrained('member', 'member_id');
            $table->foreignId('book_id')->constrained('buku', 'book_id');
            $table->date('borrow_date');
            $table->date('due_date'); // Not nullable
            $table->date('return_date')->nullable();
            $table->string('status', 20);
            $table->timestamps();
        });
        
        // Copy data from old table to new table
        DB::statement('INSERT INTO log_pinjam_buku SELECT * FROM log_pinjam_buku_old');
        
        // Drop the old table
        Schema::drop('log_pinjam_buku_old');
    }
};
