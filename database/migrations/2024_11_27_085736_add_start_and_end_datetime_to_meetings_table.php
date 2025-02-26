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
        $table->datetime('start_datetime')->nullable();
        $table->datetime('end_datetime')->nullable();
    });
}

public function down()
{
    Schema::table('meetings', function (Blueprint $table) {
        $table->dropColumn(['start_datetime', 'end_datetime']);
    });
}

};
