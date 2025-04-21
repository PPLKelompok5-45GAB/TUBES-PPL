<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_pinjam_buku', function (Blueprint $table) {
            $table->increments('loan_id');
            $table->integer('book_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'late', 'lost', 'pending', 'approved', 'rejected']);
            $table->integer('overdue_count')->default(0);
            $table->foreign('book_id')->references('book_id')->on('buku')->onDelete('cascade');
            $table->foreign('member_id')->references('member_id')->on('member')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_pinjam_buku');
    }
};
