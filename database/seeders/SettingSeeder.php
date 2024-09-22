<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Setting::create([
            'fraction_commuting_time'   =>  0,
            'fraction_leave_time'       =>  0,
            'fraction_behind_time'      =>  0,
            'fraction_leave_early'      =>  0,
            'round_up_commuting_time'   =>  1,
            'truncate_leave_time'       =>  1,
            'round_up_behind_time'      =>  1,
            'truncate_leave_early'      =>  1,
            'consecutive_work'      =>      7,
        ]);
    }
}
