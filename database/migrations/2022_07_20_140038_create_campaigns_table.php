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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->integer('step');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->text('phone_no')->nullable();
            $table->string('title')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();

            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('bussines_categories')->onDelete('cascade');

            $table->unsignedBigInteger('vertical_id')->nullable();
            $table->foreign('vertical_id')->references('id')->on('company_verticals')->onDelete('cascade');

            $table->string('language')->nullable();
            $table->string('currency')->nullable();
            $table->date("start_date")->nullable();
            $table->time("start_time")->nullable();
            $table->date("end_date")->nullable();
            $table->time("end_time")->nullable();
            $table->longText("descripiton")->nullable();
            $table->string("website_url")->nullable();
            $table->string("deeplink")->nullable();
            $table->string("blog_url")->nullable();
            $table->string("facebook_url")->nullable();
            $table->string("twitter_url")->nullable();
            $table->string("linkedin_url")->nullable();
            $table->string('client_image')->nullable();
            $table->string('agent_image')->nullable();
            $table->string('publisher_image')->nullable();
            $table->string('cost_per_call')->nullable();
            $table->string('client_duration_type')->nullable();
            $table->integer('client_per_call_duration')->nullable();
            $table->float('payout_per_call')->nullable();
            $table->string('publisher_duration_type')->nullable();
            $table->integer('publisher_per_call_duration')->nullable();
            $table->string('agent_duration_type')->nullable();
            $table->integer('campaign_rate')->nullable();

            //if air time will be enabled then 2 fields paid_air_time_by and price will be shown
            $table->boolean('air_time')->default(false);
            $table->string('paid_air_time_by')->nullable();
            $table->integer('air_time_price')->nullable();

            //if recording will be enabled then 1 field call_recording_price will be shown
            $table->boolean('recording')->default(false);
            $table->integer('call_recording_price')->nullable();

            //if transcripts will be enabled then 1 field transcript_price will be shown
            $table->boolean('transcripts')->default(false);
            $table->integer('transcript_price')->nullable();

            //if call_storage will be enabled then 1 field call_storage_price will be shown
            $table->boolean('call_storage')->default(false);
            $table->integer('call_storage_price')->nullable();
            $table->enum('routing', ['standard','ivr'])->default('standard');
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
        Schema::dropIfExists('campaigns');
    }
};
