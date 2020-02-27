<?php

use Illuminate\Database\Seeder;

class ProfLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prof_levels')->insert([
            'name' => 'лица, имеющие высшее образование',
        ]);
        DB::table('prof_levels')->insert([
            'name' => 'лица, получающие высшее образование',
        ]);
        DB::table('prof_levels')->insert([
            'name' => 'лица, имеющие среднее профессиональное образование',
        ]);
        DB::table('prof_levels')->insert([
            'name' => 'лица, получающие среднее профессиональное образование',
        ]);
    }
}
