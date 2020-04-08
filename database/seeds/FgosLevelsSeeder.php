<?php

use Illuminate\Database\Seeder;

class FgosLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fgos_levels')->insert([
            'name' => 'Среднее профессиональное образование',
        ]);
        DB::table('fgos_levels')->insert([
            'name' => 'Высшее образование – бакалавриат',
        ]);
        DB::table('fgos_levels')->insert([
            'name' => 'Высшее образование – специалитет',
        ]);
        DB::table('fgos_levels')->insert([
            'name' => 'Высшее образование – магистратура',
        ]);
    }
}
