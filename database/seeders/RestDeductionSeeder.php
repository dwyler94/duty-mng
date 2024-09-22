<?php

namespace Database\Seeders;

use App\Models\RestDeduction;
use Illuminate\Database\Seeder;

class RestDeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        RestDeduction::create([
            'id'    =>  1,
            'name'  =>  '休憩時間の控除：6時間以上の勤務で1時間を自動控除'
        ]);
        RestDeduction::create([
            'id'    =>  2,
            'name'  =>  '休憩時間の控除：シフトに登録した休憩時間を控除'
        ]);
    }
}
