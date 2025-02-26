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
            $table->string('requester')->nullable();
            $table->string('chairperson')->nullable();
            $table->enum('type', ['open', 'closed'])->default('closed');
        });
    }

    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn(['requester', 'chairperson', 'type']);
        });
    }

};
