<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLectionNsis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lection_nsis', function (Blueprint $table) {
            $table->unsignedBigInteger('lection_id');
            $table->foreign('lection_id')->references('id')->on('lections');
            $table->unsignedBigInteger('nsi_id');
            $table->foreign('nsi_id')->references('id')->on('nsis');
            $table->primary(['lection_id', 'nsi_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lection_nsis');
    }
}
