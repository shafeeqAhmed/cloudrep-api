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
        Schema::create('campaign_geo_locations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city_town')->nullable();
            $table->integer('zipcode')->nullable();
            $table->float('long')->nullable();
            $table->float('lat')->nullable();
            $table->string('address')->nullable();

            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');

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
        Schema::dropIfExists('campaign_geo_locations');
    }
};
