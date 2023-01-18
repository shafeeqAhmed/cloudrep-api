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
        Schema::table('publisher_profile_items', function (Blueprint $table) {
            $table->unsignedBigInteger('dropdown_id')->nullable()->after('user_id');
            $table->foreign('dropdown_id')->references('id')->on('dropdowns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('publisher_profile_items', function (Blueprint $table) {
            $table->dropForeign(['dropdown_id']);
            $table->dropColumn('dropdown_id');
        });
    }
};
