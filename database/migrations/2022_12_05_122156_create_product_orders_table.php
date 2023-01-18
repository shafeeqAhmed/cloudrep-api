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
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customer_informations');
            $table->string('category')->nullable();
            $table->string('product')->nullable();
            $table->boolean('product_specification')->default(false);
            $table->string('brand')->nullable();
            $table->string('capacity')->nullable();
            $table->string('color')->nullable();
            $table->integer('product_price')->nullable();
            $table->boolean('delivery')->default(false);
            $table->date('delivery_date')->nullable();
            $table->time('delivery_start_time')->nullable();
            $table->time('delivery_end_time')->nullable();
            $table->boolean('pickup')->default(false);
            $table->date('pickup_date')->nullable();
            $table->time('pickup_start_time')->nullable();
            $table->time('pickup_end_time')->nullable();
            $table->string('order_notes')->nullable();
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
        Schema::dropIfExists('product_orders');
    }
};
