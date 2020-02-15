<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMiscToStageTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stage_types', function (Blueprint $table) {
            $table->boolean('is_first')->default(false);
            $table->unsignedBigInteger('previous_stage')->nullable();
            $table->foreign('previous_stage')->references('id')->on('stage_types');
            $table->unsignedBigInteger('next_stage')->nullable();
            $table->foreign('next_stage')->references('id')->on('stage_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stage_types', function (Blueprint $table) {
            //
        });
    }
}
