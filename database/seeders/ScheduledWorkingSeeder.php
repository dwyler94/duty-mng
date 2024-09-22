<?php

namespace Database\Seeders;

use App\Models\ScheduledWorking;
use Illuminate\Database\Seeder;

class ScheduledWorkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $count = 1;
        for($i = 1; $i <= 20; $i++)
        {
            for ($j = 1; $j <= 12; $j++)
            {
                $t = new ScheduledWorking();
                $t->fill([
                    'year_id'   =>  $i,
                    'month'     =>  $j,
                    'scheduled_working_days' => 20,
                    'scheduled_working_hours'=> 160
                ]);
            }
        }
    }
}
