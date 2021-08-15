<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToMtos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mtos', function (Blueprint $table) {
            $table->integer('position')->default(0);
            $table->mediumText('note')->nullable();
            $table->boolean('is_base_for_lection')->default(false);
            $table->boolean('is_base_for_practice')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtos', function (Blueprint $table) {
            //
        });
    }
}
