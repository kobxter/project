<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->json('participants')->nullable()->change(); // ปรับให้ฟิลด์ participants สามารถเป็น null ได้
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            //
        });
    }
};
