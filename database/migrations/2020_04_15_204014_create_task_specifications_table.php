<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_specifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->longText('description')->nullable();
            $table->longText('place')->nullable();;
            $table->longText('source')->nullable();;
            $table->integer('time')->nullable();;
            $table->longText('portfolio_structure_req')->nullable();;
            $table->longText('portfolio_presentation_req')->nullable();;
            $table->longText('portfolio_procedure')->nullable();;
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
        Schema::dropIfExists('task_specifications');
    }
}
