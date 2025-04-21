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
        Schema::create('log_stock_buku', function (Blueprint $table) {
            $table->increments('log_id'); // Make log_id auto-increment primary key
            $table->integer('book_id')->unsigned();
            $table->date('entry_date');
            $table->integer('qty_added')->default(0);
            $table->integer('qty_removed')->default(0);
            $table->text('notes')->nullable();
            $table->foreign('book_id')->references('book_id')->on('buku')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_stock_buku');
    }
};
