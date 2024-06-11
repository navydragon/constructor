<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParametersToDpps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dpps', function (Blueprint $table) {
            $table->boolean('has_eok')->default(false);
            $table->string('eok_url')->nullable();
            $table->string('eok_login')->nullable();
            $table->string('eok_password')->nullable();
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
            $table->dropColumn('has_eok');
            $table->dropColumn('eok_url');
            $table->dropColumn('eok_login');
            $table->dropColumn('eok_password');
        });
    }
}
