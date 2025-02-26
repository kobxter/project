<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // คอลัมน์ Primary Key
            $table->string('title'); // ชื่อเหตุการณ์
            $table->dateTime('start'); // วันที่เริ่มต้น
            $table->dateTime('end')->nullable(); // วันที่สิ้นสุด (อาจเป็น null)
            $table->timestamps(); // created_at และ updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('events'); // ลบตารางหาก rollback migration
    }
}
