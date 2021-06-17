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
            'shortname' => 'superadmin',
        ]);
        DB::table('rights')->insert([
            'name' => 'Администратор',
            'shortname' => 'admin',
        ]);
        DB::table('rights')->insert([
            'name' => 'Пользователь',
            'shortname' => 'user',
        ]);
    }
}
