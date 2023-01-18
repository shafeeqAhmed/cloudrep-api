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
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('business_category')->nullable()->after('ip_address');
            $table->foreign('business_category')->references('id')->on('bussines_categories')->onDelete('cascade');

            $table->string('business_name')->nullable()->after('ip_address');
            $table->dateTime('code_expired_date_time')->nullable()->after('ip_address');
            $table->string('verification_code')->nullable()->after('ip_address');
            $table->boolean('is_verified')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropColumn('is_verified');
            $table->dropColumn('verification_code');
            $table->dropColumn('code_expired_date_time');
            $table->dropColumn('business_name');
            $table->dropForeign(['business_category']);
            $table->dropColumn('business_category');
        });
    }
};
