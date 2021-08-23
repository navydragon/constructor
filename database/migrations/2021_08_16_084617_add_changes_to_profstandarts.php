<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangesToProfstandarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prof_standarts', function (Blueprint $table) {
            $table->string('nameText')->nullable();
            $table->string('nameCode')->nullable();
            $table->date('orderDate')->nullable();
            $table->string('orderNumber')->nullable();
            $table->date('registrationDate')->nullable();
            $table->string('registrationNumber')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profstandarts', function (Blueprint $table) {
            //
        });
    }
}
