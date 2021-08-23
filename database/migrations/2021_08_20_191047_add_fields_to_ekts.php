<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEkts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ekts', function (Blueprint $table) {
            $table->string('rank');
            $table->string('organType');
            $table->string('documentType');
            $table->string('editionNumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ekts', function (Blueprint $table) {
            //
        });
    }
}
