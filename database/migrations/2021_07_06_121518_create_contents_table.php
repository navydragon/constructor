<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->default(0);
            $table->longText('name')->nullable();
            $table->longText('text')->nullable();
            $table->unsignedBigInteger('lection_id');
            $table->foreign('lection_id')->references('id')->on('lections');
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
        Schema::dropIfExists('contents');
    }
}
