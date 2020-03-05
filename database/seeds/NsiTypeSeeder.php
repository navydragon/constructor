<?php

use Illuminate\Database\Seeder;

class NsiTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nsi_types')->insert([
            'name' => 'Нормативные правовые акты, нормативная техническая документация, иная документация'
        ]);
        DB::table('nsi_types')->insert([
            'name' => 'Учебники, монографии'
        ]);
        DB::table('nsi_types')->insert([
            'name' => 'Интернет ресурсы'
        ]);
        DB::table('nsi_types')->insert([
            'name' => 'Электронно-библиотечная система'
        ]);
    }
}
