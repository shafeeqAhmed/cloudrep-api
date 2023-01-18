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
        Schema::create('ivr_nodes_retries', function (Blueprint $table) {
            $table->id();
            $table->string('node_type');
            $table->string('node_uuid');
            $table->text('call_sid');
            $table->integer('no_of_retires')->default(1);
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
        Schema::dropIfExists('ivr_nodes_retries');
    }
};
