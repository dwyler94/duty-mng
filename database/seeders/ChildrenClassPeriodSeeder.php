<?php

namespace Database\Seeders;

use App\Models\ChildrenClassPeriod;
use App\Models\Child;
use Illuminate\Database\Seeder;

class ChildrenClassPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Child::get() as $child) {
            ChildrenClassPeriod::create([
                'child_id' => $child->id,
                'class_id' => $child->class_id,
                'start_date' => $child->admission_date
            ]);
        }
    }
}
