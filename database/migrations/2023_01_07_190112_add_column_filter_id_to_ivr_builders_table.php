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
            $table->foreignId('parent_filter_id')->after('ivr_id')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->integer('priority')->after('parent_filter_id')->default(0);
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
            $table->dropColumn('parent_filter_id');
            $table->dropColumn('priority');
        });
    }
};
