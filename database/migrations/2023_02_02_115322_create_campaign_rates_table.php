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
        Schema::create('campaign_rates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('target_id')->nullable()->references('id')->on('target_listings')->onDelete('set null');
            $table->foreignId('number_id')->nullable()->references('id')->on('twillio_numbers')->onDelete('set null');
            $table->string('currency')->nullable();
            $table->enum('type', ['target', 'publisher_number']);
            $table->float('cost_per_call')->nullable();
            $table->integer('cost_per_call_duration')->nullable();
            $table->float('payout_per_call')->nullable();
            $table->integer('payout_per_call_duration')->nullable();
            $table->foreignId('campaign_id')->nullable()->references('id')->on('campaigns')->onDelete('set null');
            $table->foreignId('publisher_id')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->foreignId('client_id')->nullable()->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('campaign_rates');
    }
};
