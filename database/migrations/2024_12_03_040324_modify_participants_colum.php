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
        $table->json('participants')->change(); // แปลงฟิลด์เป็น JSON
    });
}

public function down()
{
    Schema::table('meetings', function (Blueprint $table) {
        $table->text('participants')->change(); // ย้อนกลับเป็น text หากต้องการ
    });
}

};
