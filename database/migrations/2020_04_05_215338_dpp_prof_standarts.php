<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DppProfStandarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dpp_prof_standarts', function (Blueprint $table) {
            $table->unsignedBigInteger('ish_version_id')->nullable();
            $table->foreign('ish_version_id')->references('id')->on('ish_versions');
            $table->unsignedBigInteger('prof_standart_id')->nullable();
            $table->foreign('prof_standart_id')->references('id')->on('prof_standarts');
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
