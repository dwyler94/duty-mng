<?php

namespace Database\Seeders;

use App\Models\ChildrenClass;
use Illuminate\Database\Seeder;

class ChildrenClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ChildrenClass::create([
            'id'    =>  1,
            'name'  =>  '0歳児'
        ]);
        ChildrenClass::create([
            'id'    =>  2,
            'name'  =>  '1歳児'
        ]);
        ChildrenClass::create([
            'id'    =>  3,
            'name'  =>  '2歳児'
        ]);
        ChildrenClass::create([
            'id'    =>  4,
            'name'  =>  '3歳児'
        ]);
        ChildrenClass::create([
            'id'    =>  5,
            'name'  =>  '4歳児'
        ]);
        ChildrenClass::create([
            'id'    =>  6,
            'name'  =>  '5歳児'
        ]);
    }
}
