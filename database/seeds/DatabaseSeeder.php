<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // $this->call(RightsTableSeeder::class);
        // $this->call(DppTypesTableSeeder::class);
        //$this->call(RolesSeeder::class);
        //$this->call(DppStatusesTableSeeder::class);
        //$this->call(DppStagesTableSeeder::class);
        //$this->call(DppStageStatusesTableSeeder::class);
        //$this->call(ProfLevelsTableSeeder::class);
        //$this->call(QuestionTypesSeeder::class);
        //$this->call(NsiTypeSeeder::class);
        $this->call(FgosLevelsSeeder::class);
        $this->call(TaskTypeSeeder::class);
        $this->call(TaskSubjectTypeSeeped::class);
        $this->call(MtoTypeSeeder::class);
    }
}
