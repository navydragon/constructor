<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCtVersionToDpps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dpps', function (Blueprint $table) {
            $table->unsignedBigInteger('ct_version_id')->nullable();
            $table->foreign('ct_version_id')->references('id')->on('content_versions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dpps', function (Blueprint $table) {
            //
        });
    }
}
