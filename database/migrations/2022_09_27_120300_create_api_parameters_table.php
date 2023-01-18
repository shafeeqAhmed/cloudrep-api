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
        Schema::create('api_parameters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->unsignedBigInteger('api_endpoint_id')->nullable();
            $table->foreign('api_endpoint_id')->references('id')->on('api_endpoints');

            $table->string('name')->nullable();
            $table->string('data_type')->nullable();
            $table->text('description')->nullable();
            $table->text('example_data')->nullable();
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
        Schema::dropIfExists('api_parameters');
    }
};
