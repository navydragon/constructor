<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeNsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_nsis', function (Blueprint $table) {
            $table->unsignedBigInteger('knowledge_id');
            $table->foreign('knowledge_id')->references('id')->on('knowledge');
            $table->unsignedBigInteger('nsi_id');
            $table->foreign('nsi_id')->references('id')->on('nsis');
            $table->primary(['knowledge_id', 'nsi_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knowledge_nsis');
    }
}
