<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsByExpertToZuns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->boolean('is_by_expert')->nullable();
            $table->longText('expert_answer')->nullable();
        });
        Schema::table('abilities', function (Blueprint $table) {
            $table->boolean('is_by_expert')->nullable();
            $table->longText('expert_answer')->nullable();
        });
        Schema::table('knowledge', function (Blueprint $table) {
            $table->boolean('is_by_expert')->nullable();
            $table->longText('expert_answer')->nullable();
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
