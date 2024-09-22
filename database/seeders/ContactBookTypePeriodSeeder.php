<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\ContactBookTypePeriod;
use Illuminate\Database\Seeder;

class ContactBookTypePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Child::get() as $child) {
            ContactBookTypePeriod::create([
                'child_id' => $child->id,
                'type' => ContactBookTypePeriod::typeByBirthday($child->birthday),
                'start_date' => $child->admission_date
            ]);
        }
    }
}
