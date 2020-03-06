<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbilityNsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ability_nsis', function (Blueprint $table) {
            $table->unsignedBigInteger('ability_id');
            $table->foreign('ability_id')->references('id')->on('abilities');
            $table->unsignedBigInteger('nsi_id');
            $table->foreign('nsi_id')->references('id')->on('nsis');
            $table->primary(['ability_id', 'nsi_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ability_nsis');
    }
}
