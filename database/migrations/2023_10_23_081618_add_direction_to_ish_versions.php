<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirectionToIshVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ish_versions', function (Blueprint $table) {
            // Добавляем колонку direction
            $table->unsignedBigInteger('direction_id')->nullable();

            // Добавляем внешний ключ
            $table->foreign('direction_id')
                  ->references('id')
                  ->on('program_directions')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ish_versions', function (Blueprint $table) {
            // Удаляем внешний ключ
            $table->dropForeign(['direction_id']);

            // Удаляем колонку direction
            $table->dropColumn('direction_id');
        });
    }
}
