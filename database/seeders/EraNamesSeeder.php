<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EraNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('era_names')->insert([
            'id' => 1,
            'name' => "明治",
            'short_name' => "M",
            'start_date' => "1868-01-25",
            'end_date' => "1912-07-29",
        ]);
        \DB::table('era_names')->insert([
            'id' => 2,
            'name' => "大正",
            'short_name' => "T",
            'start_date' => "1912-07-30",
            'end_date' => "1926-12-24",
        ]);
        \DB::table('era_names')->insert([
            'id' => 3,
            'name' => "昭和",
            'short_name' => "S",
            'start_date' => "1926-12-25",
            'end_date' => "1989-01-07",
        ]);
        \DB::table('era_names')->insert([
            'id' => 4,
            'name' => "平成",
            'short_name' => "H",
            'start_date' => "1989-01-08",
            'end_date' => "2019-04-30",
        ]);
        \DB::table('era_names')->insert([
            'id' => 5,
            'name' => "令和",
            'short_name' => "R",
            'start_date' => "2019-05-01",
            'end_date' => "2100-01-01",
        ]);
    }
}
