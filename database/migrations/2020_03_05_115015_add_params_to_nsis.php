<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParamsToNsis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nsis', function (Blueprint $table) {
            $table->string('authors', 2000)->nullable();
            $table->string('output', 2000)->nullable();
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users');
            $table->unsignedBigInteger('ish_version_id');
            $table->foreign('ish_version_id')->references('id')->on('ish_versions');
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
