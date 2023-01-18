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
        Schema::create('ivr_builder_filter_conditions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->integer('campaign_id')->nullable();
            $table->integer('ivr_builder_id')->nullable();
            $table->integer('tag_id')->default(0);
            $table->integer('tag_operator_id')->default(0);
            $table->string('type')->nullable();
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
        Schema::dropIfExists('ivr_builder_filter_conditions');
    }
};
