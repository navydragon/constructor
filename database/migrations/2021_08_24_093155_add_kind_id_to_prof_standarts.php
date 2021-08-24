<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKindIdToProfStandarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prof_standarts', function (Blueprint $table) {
            $table->unsignedBigInteger('kind_id')->nullable();
            $table->foreign('kind_id')->references('id')->on('prof_standart_kinds');
            $table->string('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prof_standarts', function (Blueprint $table) {
            //
        });
    }
}
