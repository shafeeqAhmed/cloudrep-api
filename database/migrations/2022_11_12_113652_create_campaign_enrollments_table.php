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
        Schema::create('campaign_enrollment', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('users');

            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->foreign('publisher_id')->references('id')->on('users');

            $table->integer('publisher_DID')->default(0);
            $table->enum('status',['Active','Inactive'])->default('Active');

            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');

            $table->dateTime('publisher_timezone');
            $table->dateTime('client_timezone');
            
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
        Schema::dropIfExists('campaign_enrollment');
    }
};
