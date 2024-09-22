<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $regions =  [
            '北海道エリア',
            '宮城エリア',
            '福島エリア',
            '東京エリア',
            '大阪エリア',
            '福岡エリア',
            '沖縄エリア'
        ];
        $i = 1;
        foreach($regions as $regionItem)
        {
            $t = new Region();
            $t->name = $regionItem;
            $t->id = $i;
            $t->save();
            $i++;
        }
    }
}
