<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinistryIdToNsis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nsis', function (Blueprint $table) {
            $table->unsignedBigInteger('nsiMinistry')->nullable();
            $table->foreign('nsiMinistry')->references('id')->on('ministries');
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
