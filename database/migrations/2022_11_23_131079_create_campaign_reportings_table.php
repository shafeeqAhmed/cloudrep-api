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
        Schema::create('campaign_reportings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->text('parent_call_sid')->nullable();
            $table->text('call_sid')->nullable();
            $table->dateTime('call_date')->nullable();
            $table->foreignId('campaign_id')->nullable()->references('id')->on('campaigns')->onDelete('set null');
            $table->foreignId('publisher_id')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->foreignId('client_id')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->string('caller_id')->nullable();
            $table->string('dialed')->nullable();
            $table->time('time_to_call')->nullable();
            $table->boolean('duplicate')->default(false);
            $table->string('hangup_reason')->nullable();
            $table->time('time_to_connect')->nullable();
            $table->foreignId('target_id')->nullable()->references('id')->on('target_listings')->onDelete('set null');
            $table->float('revenue')->nullable();
            $table->float('payout')->nullable();
            $table->time('duration')->nullable();
            $table->text('recording')->nullable();
            $table->float('profit')->nullable();
            $table->enum('hangup', ['system', 'caller', 'target'])->nullable()->default('system');
            $table->string('caller_country')->nullable();
            $table->string('call_status')->nullable();
            $table->dateTime('initiated_at')->nullable();
            $table->dateTime('ringing_at')->nullable();
            $table->dateTime('answered_at')->nullable();
            $table->dateTime('completed_at')->nullable();
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
        Schema::dropIfExists('campaign_reportings');
    }
};
