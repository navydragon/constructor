<?php

use Illuminate\Database\Seeder;
use App\StageType;
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
            'name' => 'Проектирование ОМ'
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
        DB::table('stage_types')->insert([
            'name' => 'Ввод исходных данных'
        ]);

        $st = StageType::find(1);
        $st->previous_stage = 6;
        $st->next_stage = 2;
        $st->save();

        $st = StageType::find(2);
        $st->previous_stage = 1;
        $st->next_stage = 3;
        $st->save();

        $st = StageType::find(3);
        $st->previous_stage = 2;
        $st->next_stage = 4;
        $st->save();

        $st = StageType::find(4);
        $st->previous_stage = 3;
        $st->next_stage = 5;
        $st->save();

        $st = StageType::find(5);
        $st->previous_stage = 4;
        $st->next_stage = null;
        $st->save();

        $st = StageType::find(6);
        $st->previous_stage = null;
        $st->next_stage = 1;
        $st->is_first = true;
        $st->save();
    }
}
