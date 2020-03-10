<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypologyPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typology_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('typology_id');
            $table->foreign('typology_id')->references('id')->on('typologies');
            $table->string('name',2000);
            $table->boolean('not_necessary')->default(false);
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
        Schema::dropIfExists('typology_parts');
    }
}
