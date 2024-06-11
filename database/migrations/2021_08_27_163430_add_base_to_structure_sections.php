<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBaseToStructureSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('structure_sections', function (Blueprint $table) {
            $table->unsignedBigInteger('dtp_id')->nullable();
            $table->foreign('dtp_id')->references('id')->on('dpp_typology_parts');
            $table->unsignedBigInteger('knowledge_id')->nullable();
            $table->foreign('knowledge_id')->references('id')->on('knowledge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('structure_sections', function (Blueprint $table) {
            //
        });
    }
}
