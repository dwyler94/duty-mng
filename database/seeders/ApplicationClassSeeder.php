<?php

namespace Database\Seeders;

use App\Models\ApplicationClass;
use Illuminate\Database\Seeder;

class ApplicationClassSeeder extends Seeder
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
            '遅刻',
            '早退',
            'その他',

        ];
        $i = 1;
        foreach($data as $item)
        {
            $t = new ApplicationClass();
            $t->id = $i;
            $t->name = $item;
            $t->save();
            $i++;
        }
    }
}
