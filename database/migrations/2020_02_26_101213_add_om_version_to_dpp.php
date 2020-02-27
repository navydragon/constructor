<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOmVersionToDpp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dpps', function (Blueprint $table) {
            $table->unsignedBigInteger('om_version_id')->nullable();
            $table->foreign('om_version_id')->references('id')->on('om_versions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dpp', function (Blueprint $table) {
            //
        });
    }
}
