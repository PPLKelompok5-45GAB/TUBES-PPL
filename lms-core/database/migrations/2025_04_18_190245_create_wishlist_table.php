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
        Schema::create('wishlist', function (Blueprint $table) {
            $table->increments('wishlist_id'); // Make wishlist_id auto-increment primary key
            $table->integer('book_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->date('added_date')->nullable();
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
        Schema::dropIfExists('wishlist');
    }
};
