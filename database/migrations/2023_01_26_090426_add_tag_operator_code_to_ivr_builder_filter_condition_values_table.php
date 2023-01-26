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
        Schema::table('ivr_builder_filter_condition_values', function (Blueprint $table) {
            $table->string('tag_operator_code')->after('tag_operator_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ivr_builder_filter_condition_values', function (Blueprint $table) {
            $table->dropColumn('tag_operator_code');
        });
    }
};
