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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('access_to_recording',['enabled','disabled'])->default('disabled')->after('quora');
            $table->enum('calls_payout_cap', ['allow','block'])->default('allow')->after('quora');
            $table->boolean('number_creation')->default(false)->after('quora');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('number_creation');
            $table->dropColumn('calls_payout_cap');
            $table->dropColumn('access_to_recording');
        });
    }
};
