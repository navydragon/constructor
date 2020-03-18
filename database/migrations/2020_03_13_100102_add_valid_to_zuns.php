<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidToZuns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->boolean('valid')->default(false);
        });
        Schema::table('abilities', function (Blueprint $table) {
            $table->boolean('valid')->default(false);
        });
        Schema::table('knowledge', function (Blueprint $table) {
            $table->boolean('valid')->default(false);
        });
        Schema::table('competences', function (Blueprint $table) {
            $table->boolean('valid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zuns', function (Blueprint $table) {
            //
        });
    }
}
