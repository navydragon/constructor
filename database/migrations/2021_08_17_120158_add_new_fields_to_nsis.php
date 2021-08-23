<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToNsis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nsis', function (Blueprint $table) {
            $table->date('nsiDate')->nullable();
            $table->string('nsiNumber')->nullable();
            $table->string('nsiEdit')->nullable();
            $table->mediumText('nsiName')->nullable();
            $table->string('nsiApproveName')->nullable();
            $table->date('nsiProtocolDate')->nullable();
            $table->string('nsiProtocolNumber')->nullable();
            $table->string('nsiCode')->nullable();
            $table->string('nsiPeriod')->nullable();
            $table->mediumText('nsiBasis')->nullable();
            $table->mediumText('nsiAuthors')->nullable();
            $table->string('nsiEditor')->nullable();
            $table->string('nsiCity')->nullable();
            $table->integer('nsiYear')->nullable();
            $table->integer('nsiPages')->nullable();
            $table->mediumText('nsiLink')->nullable();
            $table->longText('nsiFullName')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nsis', function (Blueprint $table) {
            //
        });
    }
}
