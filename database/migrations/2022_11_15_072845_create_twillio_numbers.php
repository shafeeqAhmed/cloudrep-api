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
        Schema::create('twillio_numbers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('number_sid')->nullable();
            $table->string('number')->nullable();
            $table->string('country')->nullable();
            $table->boolean('bill_card')->default(true);
            $table->enum('type', ['local', 'mobile', 'tollFree'])->default('local');
            $table->string('name')->nullable();
            $table->date('allocated')->nullable();
            $table->date('renews')->nullable();
            $table->date('last_call_date')->nullable();
            $table->string('campaign_name')->nullable();
            $table->foreignId('campaign_id')->nullable()->references('id')->on('campaigns')->onDelete('set null');
            $table->string('number_pool')->nullable();
            $table->float('amount', 2)->nullable();
            $table->foreignId('publisher_id')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('twillio_numbers');
    }
};
