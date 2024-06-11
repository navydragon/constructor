<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIshVersionIdToQualificationRequrements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qualification_requirements', function (Blueprint $table) {

            $table->unsignedBigInteger('ish_version_id')->nullable();

            $table->foreign('ish_version_id')
                  ->references('id')
                  ->on('ish_versions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qualification_requirements', function (Blueprint $table) {
             // Удаляем внешний ключ
             $table->dropForeign(['ish_version_id']);

             // Удаляем колонку direction
             $table->dropColumn('ish_version_id');
        });
    }
}
