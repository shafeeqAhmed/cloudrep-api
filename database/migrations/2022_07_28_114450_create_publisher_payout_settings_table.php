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
        Schema::create('campaign_publisher_payout_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->enum('payout_type', ['fixed_amount', 'revshare_percentage'])->default('fixed_amount');
            
            //this is for when payout_type will fixed_amount
            $table->string('payout_on')->nullable();
            $table->integer('length')->nullable();

            // this is for both fixed_amount and revshare_percentage
            $table->integer('payout_amount')->nullable();

            //for when payout_type will revshare_percentage
            $table->boolean('revshare_payout_limits')->default(false);
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();

            $table->enum('duplicate_payouts',['enable','disable','time_limit'])->default('disable');

            //these two will be shown when duplicate_payouts will be set to time_limit
            $table->integer('days')->nullable();
            $table->integer('hours')->nullable();
            
            $table->boolean('payout_hours')->default(false);

            //these three things will be shown when payout_hours will be true
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();

            //Add Break Section when add break button will be clicked in duplicate_payouts
            $table->string('break_duration')->nullable();
            $table->time('start_break_time')->nullable();
            $table->string('time_zone')->nullable();
            

            $table->boolean('limit_payout')->default(false);

            //following attributes will be shown when limit_payout will be true
            $table->boolean('global_cap')->default(false);
            $table->boolean('global_payout_cap')->default(false);
            $table->boolean('monthly_cap')->default(false);
            $table->boolean('monthly_payout_cap')->default(false);
            $table->boolean('daily_cap')->default(false);
            $table->boolean('daily_payout_cap')->default(false);
            $table->boolean('hourly_cap')->default(false);
            $table->boolean('hourly_payout_cap')->default(false);
            $table->boolean('concurrency_cap')->default(false);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns');

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
        Schema::dropIfExists('campaign_publisher_payout_settings');
    }
};
