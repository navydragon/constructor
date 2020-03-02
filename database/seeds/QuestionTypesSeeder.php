<?php

use Illuminate\Database\Seeder;

class QuestionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('question_types')->insert([
            'name' => 'Выбор одного правильного ответа',
        ]);
        DB::table('question_types')->insert([
            'name' => 'Выбор нескольких правильных ответов',
        ]);
        DB::table('question_types')->insert([
            'name' => 'Вопрос с открытым ответом',
        ]);
        DB::table('question_types')->insert([
            'name' => 'Установление последовательности',
        ]);
        DB::table('question_types')->insert([
            'name' => 'Установление соответствия',
        ]);
    }
}
