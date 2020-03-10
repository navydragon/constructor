<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_links', function (Blueprint $table) {
            $table->unsignedBigInteger('knowledge_id');
            $table->foreign('knowledge_id')->references('id')->on('knowledge');
            $table->unsignedBigInteger('ability_id');
            $table->foreign('ability_id')->references('id')->on('abilities');
            $table->primary(['knowledge_id', 'ability_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knowledge_links');
    }
}
