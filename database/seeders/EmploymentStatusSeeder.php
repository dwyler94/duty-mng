<?php

namespace Database\Seeders;

use App\Models\EmploymentStatus;
use Illuminate\Database\Seeder;

class EmploymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            '正社員',
            '時短社員',
            'パート'
        ];
        $i = 1;
        foreach($data as $item)
        {
            $es = new EmploymentStatus;
            $es->name = $item;
            $es->id = $i;
            $i++;
            $es->save();
        }
    }
}
