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
        Schema::table('ivr_builder_filter_conditions', function (Blueprint $table) {
            $table->integer('target_id')->after('campaign_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ivr_builder_filter_conditions', function (Blueprint $table) {
            $table->dropColumn('target_id');
        });
    }
};
