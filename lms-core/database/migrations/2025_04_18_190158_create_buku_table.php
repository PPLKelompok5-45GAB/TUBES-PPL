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
        Schema::create('buku', function (Blueprint $table) {
            $table->increments('book_id'); // Make book_id auto-increment primary key
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('title', 255);
            $table->string('author', 255)->nullable();
            $table->string('isbn', 20)->unique()->nullable();
            $table->year('publication_year')->nullable();
            $table->string('publisher', 255)->nullable();
            $table->integer('total_stock')->default(0);
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->text('synopsis')->nullable();
            $table->integer('borrowed_qty')->default(0);
            $table->integer('available_qty')->default(0);
            $table->string('image', 255)->nullable();
            $table->foreign('category_id')->references('category_id')->on('kategori')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
