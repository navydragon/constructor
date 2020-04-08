<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDppFgoses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dpp_fgoses', function (Blueprint $table) {
            $table->unsignedBigInteger('ish_version_id')->nullable();
            $table->foreign('ish_version_id')->references('id')->on('ish_versions');
            $table->unsignedBigInteger('fgos_id')->nullable();
            $table->foreign('fgos_id')->references('id')->on('fgos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dpp_fgoses');
    }
}
