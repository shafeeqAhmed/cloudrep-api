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
            $table->unsignedBigInteger('ivr_id')->nullable()->after('parent_id');
            $table->foreign('ivr_id')->references('id')->on('ivrs')
                ->onDelete('cascade');
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
            $table->dropForeign(['ivr_id']);
            $table->dropColumn('ivr_id');
        });
    }
};
