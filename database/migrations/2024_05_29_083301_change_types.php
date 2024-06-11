<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('structure_sections', function (Blueprint $table) {
            $table->float('lection_hours_o')->nullable()->default(0)->change();
            $table->float('lection_hours_z')->nullable()->default(0)->change();
            $table->float('practice_hours_o')->nullable()->default(0)->change();
            $table->float('practice_hours_z')->nullable()->default(0)->change();
            $table->float('lab_hours_o')->nullable()->default(0)->change();
            $table->float('lab_hours_z')->nullable()->default(0)->change();
            $table->float('consult_hours')->nullable()->default(0)->change();
            $table->float('consult_hours_o')->nullable()->default(0)->change();
            $table->float('consult_hours_z')->nullable()->default(0)->change();
            $table->float('attestation_hours_o')->nullable()->default(0)->change();
            $table->float('attestation_hours_z')->nullable()->default(0)->change();
            
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
