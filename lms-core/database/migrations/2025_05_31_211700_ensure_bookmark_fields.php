<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First check if the columns already exist to avoid errors
        if (Schema::hasTable('bookmarks') && 
            !Schema::hasColumn('bookmarks', 'page_number')) {
            Schema::table('bookmarks', function (Blueprint $table) {
                $table->integer('page_number')->nullable();
            });
        }
        
        if (Schema::hasTable('bookmarks') && 
            !Schema::hasColumn('bookmarks', 'notes')) {
            Schema::table('bookmarks', function (Blueprint $table) {
                $table->text('notes')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop these columns as they are part of the core schema
    }
};
