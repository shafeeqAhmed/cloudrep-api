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
        Schema::create('campaign_agent_payout_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->integer('per_call_earning')->nullable();
            $table->boolean('commission')->default(false);

            //if commission is true the commission_type field will be enable
            $table->enum('commission_type', ['fixed_amount', 'revshare_percentage'])->default('fixed_amount');

            //if commission will be fixed_amount or revshare_percentage then this payout_on field will be enable
            $table->string('payout_on')->nullable();
            
            // this will be disable when commission field will be false otherwise it will be as user defined when the commission_type will be fixed_amount or revshare_percentage
            $table->integer('payout_amount')->nullable();

            //for when commission will revshare_percentage
            $table->boolean('revshare_payout_limits')->default(false);
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();

            $table->enum('duplicate_payouts', ['enable','disable','time_limit'])->default('disable');

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

            $table->boolean('tips')->default(false);

            $table->string('bounties_condition')->nullable();
            $table->string('bounties_operator')->nullable();
            $table->integer('bounties_value')->nullable();

            $table->string('bonus_type')->nullable();
            $table->integer('bonus_value')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('campaign_agent_payout_settings');
    }
};
