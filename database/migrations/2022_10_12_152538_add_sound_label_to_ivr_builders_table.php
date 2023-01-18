<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('ivr_builders', function (Blueprint $table) {
            $table->string('sound_label')->after('node_content_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ivr_builders', function (Blueprint $table) {
            $table->dropColumn('sound_label');
        });
    }
};
