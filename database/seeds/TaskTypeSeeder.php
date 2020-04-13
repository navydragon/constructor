<?php

use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_types')->insert([
            'name' => 'Задание на применение умений и навыков в реальных или модельных условиях',
            'short_name' => 'Практическое задание',
        ]);
        DB::table('task_types')->insert([
            'name' => 'Задание на оформление и защиту портфолио',
            'short_name' => 'Портфолио',
        ]);
    }
}
