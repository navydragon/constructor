<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStructureSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structure_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('st_version_id');
            $table->foreign('st_version_id')->references('id')->on('structure_versions');
            $table->string('name', 2000);
            $table->integer('position')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('structure_sections');
            $table->integer('lection_hours')->default(0);
            $table->integer('practice_hours')->default(0);
            $table->integer('self_hours')->default(0);
            $table->integer('total_hours')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('structure_sections');
    }
}
