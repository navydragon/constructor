<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullNameToQuals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eks', function (Blueprint $table) {
            $table->mediumText('full_name');
        });
        Schema::table('ekts', function (Blueprint $table) {
            $table->mediumText('full_name');
        });
        Schema::table('world_skills', function (Blueprint $table) {
            $table->mediumText('full_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quals', function (Blueprint $table) {
            //
        });
    }
}
