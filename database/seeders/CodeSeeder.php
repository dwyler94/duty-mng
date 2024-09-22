<?php

namespace Database\Seeders;

use App\Constants\CodeGroups;
use App\Models\Code;
use Illuminate\Database\Seeder;

class CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Code::create([
            'group' =>  CodeGroups::OVERTIME_PAY,
            'key'   =>  1,
            'value' =>  '月の所定労働時間超過分',
        ]);
        Code::create([
            'group' =>  CodeGroups::OVERTIME_PAY,
            'key'   =>  2,
            'value' =>  '通常シフト日に、シフトを超えて勤務した時間',
        ]);
        Code::create([
            'group' =>  CodeGroups::OVERTIME_PAY,
            'key'   =>  3,
            'value' =>  '実働時間のうち、１日８時間を超えた時間',
        ]);



        Code::create([
            'group' =>  CodeGroups::SALARY_DEDUCTION,
            'key'   =>  1,
            'value' =>  '月の所定労働時間不足分',
        ]);
        Code::create([
            'group' =>  CodeGroups::SALARY_DEDUCTION,
            'key'   =>  2,
            'value' =>  'シフト予定より勤務時間が少ない場合',
        ]);




        Code::create([
            'group' =>  CodeGroups::APPLICATION_DEADLINE,
            'key'   =>  1,
            'value' =>  '当日中',
        ]);
        Code::create([
            'group' =>  CodeGroups::APPLICATION_DEADLINE,
            'key'   =>  2,
            'value' =>  '当月中',
        ]);



        Code::create([
            'group' =>  CodeGroups::APPLICATION_STATUS,
            'key'   =>  1,
            'value' =>  '申請中',
        ]);
        Code::create([
            'group' =>  CodeGroups::APPLICATION_STATUS,
            'key'   =>  2,
            'value' =>  '承認',
        ]);
        Code::create([
            'group' =>  CodeGroups::APPLICATION_STATUS,
            'key'   =>  3,
            'value' =>  '却下',
        ]);
    }
}
