<?php

namespace Tests\Setup;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class WishlistTestDatabaseSetup
{
    /**
     * Set up the test database with the wishlist table structure
     * 
     * @return void
     */
    public static function setup(): void
    {
        // Create the wishlist table with the required structure
        if (!Schema::hasTable('wishlist')) {
            Schema::create('wishlist', function (Blueprint $table) {
                $table->increments('wishlist_id');
                $table->integer('book_id')->unsigned();
                $table->integer('member_id')->unsigned();
                $table->date('added_date')->nullable();
                $table->timestamps();
            });
        }

        // Create buku table if it doesn't exist (for foreign key references)
        if (!Schema::hasTable('buku')) {
            Schema::create('buku', function (Blueprint $table) {
                $table->increments('book_id');
                $table->string('title');
                $table->string('author');
                $table->string('isbn')->nullable();
                $table->integer('available_qty')->default(0);
                $table->integer('borrowed_qty')->default(0);
                $table->timestamps();
            });
        }

        // Create member table if it doesn't exist (for foreign key references)
        if (!Schema::hasTable('member')) {
            Schema::create('member', function (Blueprint $table) {
                $table->increments('member_id');
                $table->unsignedBigInteger('user_id');
                $table->string('name');
                $table->string('email');
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->timestamps();
            });
        }

        // Create users table for authentication
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('Member');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }
}
