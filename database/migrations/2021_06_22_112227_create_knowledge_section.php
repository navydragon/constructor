<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeSection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_section', function (Blueprint $table) {
            $table->unsignedBigInteger('knowledge_id');
            $table->foreign('knowledge_id')->references('id')->on('knowledge');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('structure_sections');
            $table->primary(['knowledge_id', 'section_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knowledge_section');
    }
}
