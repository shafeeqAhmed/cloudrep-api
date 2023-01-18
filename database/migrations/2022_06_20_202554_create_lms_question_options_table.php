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
        Schema::create('lms_question_options', function (Blueprint $table) {
            $table->id();
            $table->uuid('lms_question_options_uuid');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_true')->default(false);

            $table->unsignedBigInteger('question_id')->nullable();
            $table->foreign('question_id')->references('id')->on('lms_questions')->onDelete('cascade');

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
        Schema::dropIfExists('lms_question_options');
    }
};
