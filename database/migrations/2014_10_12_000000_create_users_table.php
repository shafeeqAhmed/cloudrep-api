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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uuid');
            $table->string('name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->text('phone_no')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('step', [1, 2, 3, 4, 5, 6])->comment('1=account type, 2=basic info, 3=email verification, 4= set phone number, 5=set opt, 6=complete registeration');
            $table->string('worker_sid')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('bio')->nullable();
            $table->text('birth_date')->nullable();
            $table->string('country')->nullable();
            $table->text('website')->nullable();
            $table->text('twitter')->nullable();
            $table->text('facebook')->nullable();
            $table->text('google')->nullable();
            $table->text('linkedin')->nullable();
            $table->text('instagram')->nullable();
            $table->text('quora')->nullable();
            $table->boolean('can_create_a_number')->default(false);
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();
    }
};
