<?php

use Illuminate\Database\Seeder;

class DppTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dpp_types')->insert([
            'name' => 'Повышение квалификации',
            'min_hours' => 16,
            'max_hours' => 249,
        ]);
        DB::table('dpp_types')->insert([
            'name' => 'Переподготовка',
            'min_hours' => 250,
            'max_hours' => 999,
        ]);
    }
}
