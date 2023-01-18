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
        Schema::create('routing_plans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->integer('priority')->nullable();
            $table->integer('weight')->nullable();
            $table->string('name')->nullable();
            $table->string('destination')->nullable();
            $table->string('duplicate_conversation_type')->nullable();
            $table->float('revenue')->nullable();

            $table->unsignedBigInteger('routing_id')->nullable();
            $table->foreign('routing_id')->references('id')->on('routings')->onDelete('cascade');

            $table->unsignedBigInteger('target_id')->nullable();
            $table->foreign('target_id')->references('id')->on('target_listings')->onDelete('cascade');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');

            $table->integer('time_limit_days')->default(0);
            $table->integer('time_limit_hours')->default(0);
            $table->string('convert_on')->nullable();

            $table->enum('status', ['active', 'inactive', 'disable'])->nullable()->default('inactive');
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
        Schema::dropIfExists('routing_plans');
    }
};
