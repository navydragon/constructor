<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSetNullToDppTypologyParts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dpp_typology_parts', function (Blueprint $table) {
            $table->dropForeign(['typology_id']);
            $table->foreign('typology_id')->references('id')->on('typologies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dpp_typology_parts', function (Blueprint $table) {
            //
        });
    }
}
