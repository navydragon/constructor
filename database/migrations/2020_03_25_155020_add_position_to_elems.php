<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPositionToElems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('elems', function (Blueprint $table) {
            Schema::table('skills', function (Blueprint $table) {
                $table->smallInteger('position')->default(0);
            });
            Schema::table('abilities', function (Blueprint $table) {
                $table->smallInteger('position')->default(0);
            });
            Schema::table('knowledge', function (Blueprint $table) {
                $table->smallInteger('position')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
