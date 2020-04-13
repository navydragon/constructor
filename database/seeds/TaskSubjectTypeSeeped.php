<?php

use Illuminate\Database\Seeder;

class TaskSubjectTypeSeeped extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_subject_types')->insert([
            'name' => 'Не выбрано',
        ]);
        DB::table('task_subject_types')->insert([
            'name' => 'Только умение(-я)',
        ]);
        DB::table('task_subject_types')->insert([
            'name' => 'Только навык(-и)',
        ]);
        DB::table('task_subject_types')->insert([
            'name' => 'Навык(-и) и входящие в них умения в совокупности',
        ]);
    }
}
