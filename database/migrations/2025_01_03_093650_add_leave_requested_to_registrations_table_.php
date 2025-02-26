<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaveRequestedToRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->boolean('leave_requested')->default(false)->after('meeting_id'); // เพิ่มคอลัมน์ leave_requested
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('leave_requested'); // ลบคอลัมน์ leave_requested หาก rollback
        });
    }
}
