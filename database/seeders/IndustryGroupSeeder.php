<?php

namespace Database\Seeders;

use App\Models\IndustryGroup;
use Illuminate\Database\Seeder;

class IndustryGroupSeeder extends Seeder
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
            '本社・本部',
            '保育園',
            '病児病後児施設',
            '放課後サービス',
        ];
        $i = 1;
        foreach($data as $item)
        {
            $t = new IndustryGroup;
            $t->name = $item;
            $t->id = $i;
            $t->save();
            $i++;
        }
    }
}
