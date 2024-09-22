<?php

namespace Database\Seeders;

use App\Models\OfficeGroup;
use Illuminate\Database\Seeder;

class OfficeGroupSeeder extends Seeder
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
            '仙台本社',
            '大阪本社',
            '沖縄本社',
        ];
        $i = 1;
        foreach ($data as $item)
        {
            $t = new OfficeGroup;
            $t->name = $item;
            $t->id = $i;
            $t->save();
            $i++;
        }
    }
}
