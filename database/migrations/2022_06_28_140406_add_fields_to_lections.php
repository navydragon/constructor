<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToLections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lections', function (Blueprint $table) {
            $table->boolean('superviser')->default(0);
            $table->unsignedBigInteger('superviser_id')->nullable();
            $table->foreign('superviser_id')->references('id')->on('users');
            $table->unsignedBigInteger('normocontroller_id')->nullable();
            $table->foreign('normocontroller_id')->references('id')->on('users');
            $table->boolean('normocontroller')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lections', function (Blueprint $table) {
            //
        });
    }
}
