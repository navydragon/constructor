<?php

use Illuminate\Database\Seeder;

class MtoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mto_types')->insert([
            'name' => 'Помещения'
        ]);
        DB::table('mto_types')->insert([
            'name' => 'Мебель'
        ]);
        DB::table('mto_types')->insert([
            'name' => 'Оборудование'
        ]);
        DB::table('mto_types')->insert([
            'name' => 'Расходные материалы'
        ]);
        DB::table('mto_types')->insert([
            'name' => 'Программное обеспечение'
        ]);
        DB::table('mto_types')->insert([
            'name' => 'Транспортные средства'
        ]);
        DB::table('mto_types')->insert([
            'name' => 'Иные'
        ]);
        DB::table('mto_types')->insert([
            'parent_id' => 1,
            'name' => 'Для лекционных занятий'
        ]);
        DB::table('mto_types')->insert([
            'parent_id' => 1,
            'name' => 'Для практических занятий'
        ]);
        DB::table('mto_types')->insert([
            'parent_id' => 1,
            'name' => 'Для лабораторных занятий'
        ]);
        DB::table('mto_types')->insert([
            'parent_id' => 2,
            'name' => 'Учебных классов'
        ]);
        DB::table('mto_types')->insert([
            'parent_id' => 2,
            'name' => 'Производственных помещений'
        ]);
        DB::table('mto_types')->insert([
            'parent_id' => 3,
            'name' => 'Учебных классов'
        ]);
        DB::table('mto_types')->insert([
            'parent_id' => 3,
            'name' => 'Производственных помещений'
        ]);
        DB::table('mto_types')->insert([
            'parent_id' => 5,
            'name' => 'Офисное'
        ]);
        DB::table('mto_types')->insert([
            'parent_id' => 5,
            'name' => 'Специализированное'
        ]);
    }
}
