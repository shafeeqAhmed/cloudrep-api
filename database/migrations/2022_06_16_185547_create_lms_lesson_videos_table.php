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
        Schema::create('lms_lesson_videos', function (Blueprint $table) {
            $table->id();
            $table->uuid('lms_lesson_video_uuid');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('video_url');
            $table->text('duration')->nullable();
            $table->text('video_thumbnail')->nullable();
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->foreign('lesson_id')->references('id')->on('lms_lessons');
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
        Schema::dropIfExists('lms_lesson_videos');
    }
};
