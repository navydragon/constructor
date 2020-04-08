<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDppDolgKvals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dpp_dolg_kvals', function (Blueprint $table) {
            $table->unsignedBigInteger('ish_version_id')->nullable();
            $table->foreign('ish_version_id')->references('id')->on('ish_versions');
            $table->unsignedBigInteger('dolg_kval_id')->nullable();
            $table->foreign('dolg_kval_id')->references('id')->on('dolg_kvals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_dpp_dolg_kvals');
    }
}
