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
        Schema::create('ivr_builder_filter_condition_values', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('tag_operator_value')->nullable();
            $table->foreignId('filter_condition_id')->nullable()->references('id')->on('ivr_builder_filter_conditions')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ivr_builder_filter_condition_values');
    }
};
