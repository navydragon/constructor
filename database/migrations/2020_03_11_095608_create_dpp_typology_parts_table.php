<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDppTypologyPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dpp_typology_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dpp_id');
            $table->foreign('dpp_id')->references('id')->on('dpps');
            $table->unsignedBigInteger('typology_id')->nullable();
            $table->foreign('typology_id')->references('id')->on('typologies');
            $table->unsignedBigInteger('ish_version_id')->nullable();
            $table->foreign('ish_version_id')->references('id')->on('ish_versions');
            $table->string('name',2000);
            $table->boolean('not_necessary')->default(false);
            $table->integer('position')->default(0);
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
        Schema::dropIfExists('dpp_typology_parts');
    }
}
