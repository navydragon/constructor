<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypologyToIshVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ish_versions', function (Blueprint $table) {
            $table->unsignedBigInteger('typology_id')->nullable();
            $table->foreign('typology_id')->references('id')->on('typologies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ish_versions', function (Blueprint $table) {
            //
        });
    }
}
