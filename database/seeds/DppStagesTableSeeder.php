<?php

use Illuminate\Database\Seeder;

class DppStagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stage_types')->insert([
            'name' => 'Проектирование ПК и ЗУН'
        ]);
        DB::table('stage_types')->insert([
            'name' => 'Проектирование ФОС'
        ]);
        DB::table('stage_types')->insert([
            'name' => 'Проектирование структуры ДПП'
        ]);
        DB::table('stage_types')->insert([
            'name' => 'Проектирование УММ'
        ]);
        DB::table('stage_types')->insert([
            'name' => 'Утверждение'
        ]);
    }
}
