<?php

namespace Database\Seeders;

use App\Models\ChildrenAttendence;
use Illuminate\Database\Seeder;

class ChildrenAttendenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($c = 1; $c < 100; $c++) {
            ChildrenAttendence::create([
                'commuting_time' => '2022-06-15 08:00:00',
                'leave_time' => '2022-06-15 17:30:00',
                'date' => '2022-06-15',
                'month' => 6,
                'day' => 15,
                'year_id' => 1,
                'child_id' => 28 + $c,
            ]);
        }
    }
}
