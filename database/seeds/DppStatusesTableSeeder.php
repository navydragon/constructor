<?php

use Illuminate\Database\Seeder;

class DppStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dpp_statuses')->insert([
            'name' => 'Работа не начата',
        ]);
        DB::table('dpp_statuses')->insert([
            'name' => 'В работе',
        ]);
        DB::table('dpp_statuses')->insert([
            'name' => 'Ожидает подтверждения эксперта',
        ]);
        DB::table('dpp_statuses')->insert([
            'name' => 'Ожидает подтверждения валидатора',
        ]);
        DB::table('dpp_statuses')->insert([
            'name' => 'Одобрена',
        ]);
        DB::table('dpp_statuses')->insert([
            'name' => 'Подтверждена',
        ]);
        DB::table('dpp_statuses')->insert([
            'name' => 'Требуются исправления',
        ]);
        DB::table('dpp_statuses')->insert([
            'name' => 'Неактуальна',
        ]);
    }
}
