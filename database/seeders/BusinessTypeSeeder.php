<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        BusinessType::create([
            'id'    =>  1,
            'label' =>  '企業主導型保育事業所',
            'sort_no'   =>  1,
        ]);
        BusinessType::create([
            'id'    =>  2,
            'label' =>  '認可保育所',
            'sort_no'   =>  2,
        ]);
        BusinessType::create([
            'id'    =>  3,
            'label' =>  '小規模保育事業所',
            'sort_no'   =>  3,
        ]);
        BusinessType::create([
            'id'    =>  4,
            'label' =>  '放課後等デイサービス / 児童発達支援事業所',
            'sort_no'   =>  4,
        ]);
    }
}
