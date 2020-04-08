<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFgosLevelIdToFgos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fgos', function (Blueprint $table) {
            $table->unsignedBigInteger('fgos_level_id');
            $table->foreign('fgos_level_id')->references('id')->on('fgos_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fgos', function (Blueprint $table) {
            //
        });
    }
}
