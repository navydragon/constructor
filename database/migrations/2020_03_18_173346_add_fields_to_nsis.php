<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToNsis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nsis', function (Blueprint $table) {
            $table->string('fullname',2000)->nullable();
            $table->string('old_name',2000)->nullable();
            $table->string('start_date',100)->nullable();
            $table->string('accept_date',100)->nullable();
            $table->string('accept_number',100)->nullable();
            $table->string('accept_odm',100)->nullable();
            $table->string('odm_number',100)->nullable();
            $table->string('npa_type',100)->nullable();
            $table->string('city',100)->nullable();
            $table->string('year',20)->nullable();
            $table->string('pages',10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nsis', function (Blueprint $table) {
            //
        });
    }
}
