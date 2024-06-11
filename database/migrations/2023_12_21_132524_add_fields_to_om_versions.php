<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOmVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('om_versions', function (Blueprint $table) {
            $table->integer('optional_tasks')->default(0);
            $table->integer('test_questions')->default(30);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('om_versions', function (Blueprint $table) {
            $table->dropColumn('optional_tasks');
            $table->dropColumn('test_questions');
        });
    }
}
