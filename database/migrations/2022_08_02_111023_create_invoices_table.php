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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('invoice_number')->nullable();
            $table->date('date')->nullable();
            $table->string('terms')->nullable();
            $table->string('due_date')->nullable();
            $table->longText('description')->nullable();
            $table->float('rate')->nullable();
            $table->integer('quantity')->nullable();
            $table->float('amount')->nullable();
            $table->float('tax')->nullable();
            $table->float('discount')->nullable();
            $table->string('additional_detail')->nullable();
            $table->string('notes')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns');

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
        Schema::dropIfExists('invoices');
    }
};
