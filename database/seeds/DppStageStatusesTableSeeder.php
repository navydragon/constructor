<?php

use Illuminate\Database\Seeder;

class DppStageStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stage_statuses')->insert([
            'name' => 'Работа не начата'
        ]);
        DB::table('stage_statuses')->insert([
            'name' => 'Ожидает согласования предыдущего этапа'
        ]);
        DB::table('stage_statuses')->insert([
            'name' => 'В работе у методиста'
        ]);
        DB::table('stage_statuses')->insert([
            'name' => 'На согласовании эксперта'
        ]);
        DB::table('stage_statuses')->insert([
            'name' => 'На согласовании валидатора'
        ]);
        DB::table('stage_statuses')->insert([
            'name' => 'Согласовано'
        ]);
        DB::table('stage_statuses')->insert([
            'name' => 'На исправлении'
        ]);
        DB::table('stage_statuses')->insert([
            'name' => 'Эскалация'
        ]);
    }
}
