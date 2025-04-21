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
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->increments('post_id'); // Make post_id auto-increment primary key
            $table->integer('admin_id')->unsigned();
            $table->string('title', 255);
            $table->text('content')->nullable();
            $table->enum('status', ['draft', 'published', 'archived']);
            $table->date('post_date');
            $table->foreign('admin_id')->references('admin_id')->on('admin')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
