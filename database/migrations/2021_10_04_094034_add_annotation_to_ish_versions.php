<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnnotationToIshVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ish_versions', function (Blueprint $table) {
            $table->longText('annotationDescription')->nullable();
            $table->longText('annotationRequirements')->nullable();
            $table->longText('annotationTargets')->nullable();
            $table->longText('annotationResults')->nullable();
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
