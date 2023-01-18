<?php

use Faker\Provider\ar_EG\Text;
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
        Schema::create('ivr_builders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('node_type', ['hours', 'pixel', 'dial', 'gather', 'goto', 'hangup', 'menu', 'play', 'voicemail', 'router']);

            $table->string('time_zone')->nullable();
            $table->string('tag_name')->nullable();
            $table->integer('timeout')->nullable()->comment('The number of seconds the caller has to make a menu selection');
            $table->integer('no_of_retries')->nullable()->comment('The number of attempts the caller has to make a menu selection');
            $table->integer('no_of_reproduce')->nullable()->comment('The number time when the menu will reproduces');
            $table->enum('node_content_type', ['play', 'say'])->nullable();
            $table->text('sound')->nullable()->comment('Select or upload as audio file to playback. Only the following are supported: MP3: MPEG ADTS, layer lll, v1, 128 kbps, 44.1 kHz, JntStereo. WAV: WAVE PCM, 8,000 Hz 16 bit or 11,025 Hz 16 bit PCM ');

            $table->text('text')->nullable()->comment('The text to display');
            $table->text('text_voice')->nullable()->comment('The sex of the voice');
            $table->text('text_language')->nullable()->comment('The language of the text');


            //Hours Node Block
            // -- timezone
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->time('start_break_time')->nullable();
            $table->string('break_duration')->nullable();


            //Pixel Node Block
            $table->string('pixel_url')->nullable()->comment('The url of the pixel to fire');
            $table->integer('pixel_max_fires')->nullable()->comment('The maximum number of times this pixel can be fired');
            $table->boolean('pixel_advanced')->default(false)->comment('Advanced Setting for the pixel');

            //Following attributes will be enabled if pixel_advanced button will be true
            $table->string('pixel_method')->nullable()->comment('The Method for delivery');
            $table->string('pixel_content_type')->nullable()->comment('The ContentType of the request');
            $table->string('pixel_body')->nullable()->comment('The body of the request');
            $table->string('pixel_custom_header_1')->nullable()->comment('A custom header to send up key:value');
            $table->string('pixel_custom_header_2')->nullable()->comment('A custom header to send up key:value');

            $table->boolean('pixel_authorization')->default(false)->comment('Basic Authorization settings for the pixel');

            //Following attributes will be enabled if pixel_authorization button will be true
            $table->string('pixel_username')->nullable()->comment('The username for the authorization');
            $table->text('pixel_password')->nullable()->comment('The password for the authorization');







            //Dial Node Block
            $table->string('dial_recording_setting')->nullable()->comment('Select how you would like Ringba to handle recordings of these calls');
            // -- no_of_retries will come here
            // -- timeout
            $table->integer('dial_max_call_length')->nullable()->comment('The maximum length of this call in seconds. If the call goes over, Ringba will automatically hangup.');
            $table->integer('dial_max_recording_time')->nullable()->comment('The maximum length of the recording in seconds');
            $table->text('dial_caller_id')->nullable()->comment('Custom Caller ID the receiving party will see when answering the call. You may only use numbers in your Ringba account');
            $table->boolean('dial_wishper')->default(false)->comment('Play a message to the receiving party before the call is connected');
            // -- no_of_reproduce
            // -- sound
            // -- text
            // -- text_voice
            // -- text_language
            $table->string('dial_routing_plan')->nullable()->comment('The Routing Plan will determine who receives the call for this prompt');



            //Gather Node Block
            $table->integer('gather_max_number_of_digits')->nullable()->comment('The maximum number of digits to be prcessed in the current operation');
            $table->integer('gather_min_number_of_digits')->nullable()->comment('The minimum number of digits to be processed in the current operation');
            $table->integer('gather_valid_digits')->nullable()->comment('A list of valid digits');
            $table->string('gather_finish_on_key')->nullable()->comment('The key the caller presses to stop collecting input');
            $table->integer('gather_key_press_timeout')->nullable()->comment('Time in seconds allowed between consecutive digit inputs');

            //Goto Node Block
            $table->integer('goto_count')->nullable()->comment('The number of times this node can be passed through');
            $table->integer('goto_current_node')->nullable()->default(0);
            $table->foreignId('goto_node')->nullable()->references('id')->on('ivr_builders')->onDelete('set null')->comment('The node to which the caller will be redirected');
            // $table->integer('goto_node')->nullable()->comment('The node to which the caller will be redirected');



            //Hangup Node Block
            $table->boolean('hangup_message')->default(false)->comment('Play a message to the caller');
            // -- no_of_reproduce
            // -- sound
            // -- text
            // -- text_voice
            // -- text_language

            //VoiceMail Node Block
            // -- timeout
            $table->integer('voicemail_max_length')->nullable()->comment('The maximum length of a recording in seconds');
            $table->string('voicemail_finish_on_key')->nullable()->comment('Stop recording when the caller pressess this key');
            $table->boolean('voicemail_play_beep')->default(false)->comment('Play a beep before recording');
            $table->boolean('voicemail_email_notification')->default(false)->comment('Send an email notification if caller leaves a message');
            $table->boolean('voicemail_message')->default(false)->comment('Play a message to the caller');
            // -- no_of_reproduce
            // -- sound
            // -- text
            // -- text_voice
            // -- text_language


            $table->foreignId('on_failer')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('on_success')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_0')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_1')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_2')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_3')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_4')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_5')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_6')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_7')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_8')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
            $table->foreignId('press_9')->nullable()->references('id')->on('ivr_builders')->onDelete('set null');
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
        Schema::dropIfExists('ivr_builders');
    }
};
