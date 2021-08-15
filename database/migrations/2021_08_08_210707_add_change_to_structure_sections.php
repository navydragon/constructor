<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangeToStructureSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('structure_sections', function (Blueprint $table) {
            $table->float('lection_hours')->change();
            $table->float('practice_hours')->change();
            $table->float('self_hours')->change();
            $table->float('total_hours')->change();
            $table->float('lab_hours')->change();
            $table->float('attestation_hours')->change();
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
