<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Методист',
        ]);
        DB::table('roles')->insert([
            'name' => 'Эксперт',
        ]);
        DB::table('roles')->insert([
            'name' => 'Валидатор',
        ]);
        DB::table('roles')->insert([
            'name' => 'Супервайзер',
        ]);
    }
}
