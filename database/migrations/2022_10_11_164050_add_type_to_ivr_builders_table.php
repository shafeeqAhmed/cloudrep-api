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
            $table->string('type')->nullable()->after('ivr_id')->comment('possible value -1 to 9, success, fail');
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
            $table->dropColumn('type');
        });
    }
};
