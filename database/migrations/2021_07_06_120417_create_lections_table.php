<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ct_version_id');
            $table->foreign('ct_version_id')->references('id')->on('content_versions');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('structure_sections');
            $table->string('name', 2000);
            $table->integer('position')->default(0);
            $table->integer('symbols')->default(0);
            $table->integer('images')->default(0);
            $table->integer('formulas')->default(0);
            $table->integer('tables')->default(0);
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
        Schema::dropIfExists('lections');
    }
}
