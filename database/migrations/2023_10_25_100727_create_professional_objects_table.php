<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_objects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('text');
            $table->integer('position');
            $table->unsignedBigInteger('ish_version_id')->nullable();
            $table->timestamps();
            $table->foreign('ish_version_id')
                  ->references('id')
                  ->on('ish_versions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professional_objects');
    }
}
