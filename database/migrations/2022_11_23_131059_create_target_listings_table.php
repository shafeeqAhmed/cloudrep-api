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
        Schema::create('target_listings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('destination')->nullable();

            $table->unsignedBigInteger('routing_id')->nullable();
            $table->foreign('routing_id')->references('id')->on('routings')->onDelete('cascade');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');


            $table->enum('status', ['active', 'inactive', 'disable'])->nullable()->default('inactive');
            $table->boolean('is_primary')->default(false);
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
        Schema::dropIfExists('target_listings');
    }
};
