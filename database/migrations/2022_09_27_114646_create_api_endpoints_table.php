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
        Schema::create('api_endpoints', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->unsignedBigInteger('api_list_id')->nullable();
            $table->foreign('api_list_id')->references('id')->on('api_lists');

            $table->string('type')->nullable();
            $table->string('url')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('api_endpoints');
    }
};
