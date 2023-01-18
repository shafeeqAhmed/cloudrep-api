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
        Schema::create('twilio_number_tags', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('tag_name')->nullable();
            $table->string('tag_value')->nullable();
            $table->unsignedBigInteger('twilio_number_id')->nullable();
            $table->foreign('twilio_number_id')->references('id')->on('twillio_numbers')->onDelete('cascade');
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
        Schema::dropIfExists('twilio_number_tags');
    }
};
