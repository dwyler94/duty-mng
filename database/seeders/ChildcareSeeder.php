<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChildcareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->call([
            ChildrenClassSeeder::class,
            ChildTypesSeeder::class,
            ChildMoodTypeSeeder::class,
            ReasonForAbsenceSeeder::class
        ]);
    }
}
