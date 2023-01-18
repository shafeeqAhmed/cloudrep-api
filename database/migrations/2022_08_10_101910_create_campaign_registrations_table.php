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
        Schema::create('campaign_registrations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('username')->nuique();
            $table->string('email')->nullable();
            $table->string('title')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('address')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->string('working_state')->nullable();
            $table->boolean('working_hours')->default(false);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->string('time_zone')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('campaign_registrations');
    }
};
