<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\ChildInformation;
use Illuminate\Database\Seeder;

class ChildInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $child= Child::get();

        foreach ($child as $item) {
            ChildInformation::create([
                'child_id' => $item->id,
                'start_date' => "2022-04-01",
                'name' => $item->id,
            ]);
        }
    }
}
