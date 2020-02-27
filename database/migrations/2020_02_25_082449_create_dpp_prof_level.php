<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDppProfLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dpp_prof_levels', function (Blueprint $table) {
            $table->unsignedBigInteger('ish_version_id');
            $table->foreign('ish_version_id')->references('id')->on('ish_versions');
            $table->unsignedBigInteger('prof_level_id');
            $table->foreign('prof_level_id')->references('id')->on('prof_levels');
            $table->primary(['ish_version_id', 'prof_level_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dpp_prof_levels');
    }
}
