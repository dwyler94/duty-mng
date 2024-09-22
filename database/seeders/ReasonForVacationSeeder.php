<?php

namespace Database\Seeders;

use App\Models\ReasonForVacation;
use Illuminate\Database\Seeder;

class ReasonForVacationSeeder extends Seeder
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
            '体調不良',
            '私用',
            '育児休暇',
            '慶弔休暇',
            'リフレッシュ休暇',
        ];
        $i = 1;
        foreach($data as $item)
        {
            $t = new ReasonForVacation();
            $t->id = $i;
            $t->name = $item;
            $t->save();
            $i++;
        }
    }
}
