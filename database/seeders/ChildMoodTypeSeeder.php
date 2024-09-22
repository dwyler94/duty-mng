<?php

namespace Database\Seeders;

use App\Constants\CodeGroups;
use App\Models\Code;
use Illuminate\Database\Seeder;

class ChildMoodTypeSeeder extends Seeder
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
            'group' =>  CodeGroups::CHILD_MOOD_TYPE,
            'key'   =>  1,
            'value' =>  '普通',
        ]);
        Code::create([
            'group' =>  CodeGroups::CHILD_MOOD_TYPE,
            'key'   =>  2,
            'value' =>  '良い',
        ]);
        Code::create([
            'group' =>  CodeGroups::CHILD_MOOD_TYPE,
            'key'   =>  3,
            'value' =>  '悪い',
        ]);



        Code::create([
            'group' =>  CodeGroups::CHILD_DEFICATION_TYPE,
            'key'   =>  1,
            'value' =>  '普通',
        ]);
        Code::create([
            'group' =>  CodeGroups::CHILD_DEFICATION_TYPE,
            'key'   =>  2,
            'value' =>  '軟い',
        ]);
        Code::create([
            'group' =>  CodeGroups::CHILD_DEFICATION_TYPE,
            'key'   =>  3,
            'value' =>  '固い',
        ]);
    }
}
