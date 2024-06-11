<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOzToStructureSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('structure_sections', function (Blueprint $table) {
            $table->integer('lection_hours_o')->nullable()->default(0);
            $table->integer('lection_hours_z')->nullable()->default(0);
            $table->integer('practice_hours_o')->nullable()->default(0);
            $table->integer('practice_hours_z')->nullable()->default(0);
            $table->integer('lab_hours_o')->nullable()->default(0);
            $table->integer('lab_hours_z')->nullable()->default(0);
            $table->integer('consult_hours')->nullable()->default(0);
            $table->integer('consult_hours_o')->nullable()->default(0);
            $table->integer('consult_hours_z')->nullable()->default(0);
            $table->integer('attestation_hours_o')->nullable()->default(0);
            $table->integer('attestation_hours_z')->nullable()->default(0);
            $table->integer('attestation_form')->nullable();
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
