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
        Schema::create('lms_courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('lms_course_uuid');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->bigInteger('price')->nullable()->default(0);
            $table->string('course_image')->nullable();
            $table->string('course_duration')->nullable();
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
        Schema::dropIfExists('lms_courses');
    }
};
