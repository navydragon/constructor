<?php

use Illuminate\Database\Seeder;

class RightsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rights')->insert([
            'name' => 'Суперадминистратор',
        ]);
        DB::table('rights')->insert([
            'name' => 'Администратор',
        ]);
        DB::table('rights')->insert([
            'name' => 'Пользователь',
        ]);
    }
}
