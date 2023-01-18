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
        Schema::create('campaign_filter_reports', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('user_uuid');
            $table->string('filter_report_name')->nullable();
            $table->string('filter_user_uuid')->nullable();
            $table->string('filter_time_zone')->nullable();
            $table->string('filter_date_range')->nullable();
            $table->text('custom_filters')->nullable();
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
        Schema::dropIfExists('campaign_filter_reports');
    }
};
