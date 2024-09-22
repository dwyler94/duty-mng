<?php

namespace Database\Seeders;

use App\Constants\CodeGroups;
use App\Models\Code;
use Illuminate\Database\Seeder;

class ChildTypesSeeder extends Seeder
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
            'group' =>  CodeGroups::CHILD_TYPE,
            'key'   =>  1,
            'value' =>  '従業員枠（自社）',
        ]);
        Code::create([
            'group' =>  CodeGroups::CHILD_TYPE,
            'key'   =>  2,
            'value' =>  '従業員枠（他社）',
        ]);
        Code::create([
            'group' =>  CodeGroups::CHILD_TYPE,
            'key'   =>  3,
            'value' =>  '地域枠（通常）',
        ]);
        Code::create([
            'group' =>  CodeGroups::CHILD_TYPE,
            'key'   =>  4,
            'value' =>  '地域枠（弾力）',
        ]);
    }
}
