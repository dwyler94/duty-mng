<?php

namespace Database\Seeders;

use App\Models\Year;
use Illuminate\Database\Seeder;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 2021; $i <= 2040; $i++)
        {
            Year::create([
                'name'  =>  $i . '年度',
                'start' =>  $i . '04',
                'end'   =>  ($i + 1) . '03',
                'deadline'=>    30
            ]);
        }
    }
}
