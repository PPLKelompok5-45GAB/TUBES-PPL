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
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite, we need to handle this differently to avoid index conflicts
            
            // First check if suspended status already exists in any records
            $hasSuspended = DB::table('member')->where('status', 'suspended')->exists();
            
            // Create a backup of member data
            $memberData = DB::table('member')->get()->toArray();
            
            // Drop the existing table and recreate it
            Schema::dropIfExists('member');
            
            Schema::create('member', function (Blueprint $table) {
                $table->increments('member_id');
                $table->unsignedBigInteger('user_id')->nullable()->unique();
                $table->string('name', 255);
                $table->string('email', 255)->unique();
                $table->string('phone', 50)->nullable();
                $table->text('address')->nullable();
                $table->date('membership_date');
                // For SQLite, include the check constraint in the column definition
                $table->string('status')->check("status IN ('active', 'inactive', 'suspended')");
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
            
            // Restore data from backup
            foreach ($memberData as $member) {
                $member = (array)$member;
                DB::table('member')->insert($member);
            }
        } else {
            // Original MySQL-specific syntax
            DB::statement("ALTER TABLE member MODIFY COLUMN status ENUM('active', 'inactive', 'suspended') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite, we need to recreate without the suspended status
            
            // First update any suspended users to inactive
            DB::table('member')->where('status', 'suspended')->update(['status' => 'inactive']);
            
            // Create a backup of member data
            $memberData = DB::table('member')->get()->toArray();
            
            // Drop the existing table and recreate it
            Schema::dropIfExists('member');
            
            Schema::create('member', function (Blueprint $table) {
                $table->increments('member_id');
                $table->unsignedBigInteger('user_id')->nullable()->unique();
                $table->string('name', 255);
                $table->string('email', 255)->unique();
                $table->string('phone', 50)->nullable();
                $table->text('address')->nullable();
                $table->date('membership_date');
                // For SQLite, include the check constraint in the column definition
                $table->string('status')->check("status IN ('active', 'inactive')");
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
            
            // Restore data from backup
            foreach ($memberData as $member) {
                $member = (array)$member;
                DB::table('member')->insert($member);
            }
        } else {
            // Original MySQL-specific syntax
            DB::table('member')->where('status', 'suspended')->update(['status' => 'inactive']);
            DB::statement("ALTER TABLE member MODIFY COLUMN status ENUM('active', 'inactive') NOT NULL");
        }
    }
};