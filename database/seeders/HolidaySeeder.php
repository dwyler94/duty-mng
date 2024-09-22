<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Holiday::create([
            'date'  =>  '2022-01-01',
            'name'  =>  "元旦"
        ]);
        Holiday::create([
            'date'  =>  '2022-01-10',
            'name'  =>  "成人の日"
        ]);
        Holiday::create([
            'date'  =>  '2022-02-11',
            'name'  =>  "建国記念の日"
        ]);
        Holiday::create([
            'date'  =>  '2022-02-23',
            'name'  =>  "天皇誕生日"
        ]);
        Holiday::create([
            'date'  =>  '2022-03-21',
            'name'  =>  "春分の日"
        ]);
        Holiday::create([
            'date'  =>  '2022-04-29',
            'name'  =>  "昭和の日"
        ]);
        Holiday::create([
            'date'  =>  '2022-05-03',
            'name'  =>  "憲法記念日"
        ]);
        Holiday::create([
            'date'  =>  '2022-05-04',
            'name'  =>  "みどりの日"
        ]);
        Holiday::create([
            'date'  =>  '2022-05-05',
            'name'  =>  "こどもの日"
        ]);
        Holiday::create([
            'date'  =>  '2022-07-18',
            'name'  =>  "海の日"
        ]);
        Holiday::create([
            'date'  =>  '2022-08-11',
            'name'  =>  "山の日"
        ]);
        Holiday::create([
            'date'  =>  '2022-09-19',
            'name'  =>  "敬老の日"
        ]);
        Holiday::create([
            'date'  =>  '2022-09-23',
            'name'  =>  "秋分の日"
        ]);
        Holiday::create([
            'date'  =>  '2022-10-10',
            'name'  =>  "スポーツの日"
        ]);
        Holiday::create([
            'date'  =>  '2022-11-03',
            'name'  =>  "文化の日"
        ]);
        Holiday::create([
            'date'  =>  '2022-11-23',
            'name'  =>  "勤労感謝の日"
        ]);
    }
}
