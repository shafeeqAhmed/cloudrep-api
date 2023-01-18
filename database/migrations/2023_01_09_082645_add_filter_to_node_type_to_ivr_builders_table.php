<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('node_type_to_ivr_builders', function (Blueprint $table) {
            DB::statement("ALTER TABLE ivr_builders MODIFY node_type ENUM('hours', 'pixel', 'dial', 'gather', 'goto', 'hangup', 'menu', 'play', 'voicemail', 'router', 'filter')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('node_type_to_ivr_builders', function (Blueprint $table) {
            DB::statement("ALTER TABLE ivr_builders MODIFY node_type ENUM('hours', 'pixel', 'dial', 'gather', 'goto', 'hangup', 'menu', 'play', 'voicemail', 'router')");
        });
    }
};
