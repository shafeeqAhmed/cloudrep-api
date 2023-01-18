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
        Schema::table('campaign_geo_locations', function (Blueprint $table) {
            $table->string('file_url')->nullable()->after('campaign_id');
            $table->string('file_name')->nullable()->after('campaign_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_geo_locations', function (Blueprint $table) {
            $table->dropColumn('file_name');
            $table->dropColumn('file_url');
        });
    }
};
