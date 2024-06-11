<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEduToIshVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ish_versions', function (Blueprint $table) {
            $table->string('edu_form')->nullable();
            $table->boolean('edu_form_dot')->default(false);
            $table->boolean('edu_practic')->default(false);

            $table->string('edu_period_name')->nullable();
            $table->integer('edu_period_duration')->nullable();
            $table->unsignedBigInteger('digital_sphere_id')->nullable();
            $table->foreign('digital_sphere_id')
                  ->references('id')
                  ->on('digital_spheres')
                  ->onDelete('set null');
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
