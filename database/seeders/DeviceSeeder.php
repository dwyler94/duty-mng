<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i=1; $i < 100; $i++) {
            Device::create([
                'uuid' =>  'UUID_' . sprintf('%05d', $i),
                'office_id'   =>  $i,
            ]);
        }

    }
}
