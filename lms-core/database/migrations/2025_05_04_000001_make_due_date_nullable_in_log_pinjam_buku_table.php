<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('log_pinjam_buku', function (Blueprint $table) {
            $table->date('due_date')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('log_pinjam_buku', function (Blueprint $table) {
            $table->date('due_date')->nullable(false)->change();
        });
    }
};
