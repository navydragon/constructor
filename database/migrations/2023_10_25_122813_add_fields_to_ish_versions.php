<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToIshVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ish_versions', function (Blueprint $table) {
            $table->unsignedBigInteger('professional_field_id')->nullable();
            $table->longText('professional_sphere')->nullable();
            $table->foreign('professional_field_id')
                  ->references('id')
                  ->on('professional_fields')
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

            $table->dropForeign(['professional_field_id']);

            $table->dropColumn('professional_field_id');
            $table->dropColumn('professional_sphere');
        });
    }
}
