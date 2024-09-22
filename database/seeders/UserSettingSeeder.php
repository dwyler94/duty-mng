<?php

namespace Database\Seeders;

use App\Models\UserSetting;
use Illuminate\Database\Seeder;

class UserSettingSeeder extends Seeder
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
            [
                'user_id'   =>  1,
                'start_date'    =>  '2010-01-01',
                'end_date'      =>  '2021-03-31',
            ],
            [
                'user_id'   =>  1,
                'start_date'    =>  '2021-04-01',
                'end_date'      =>  null,
            ],
            [
                'user_id'   =>  2,
                'start_date'    =>  '2010-01-01',
                'end_date'      =>  '2021-03-31',
            ],
            [
                'user_id'   =>  2,
                'start_date'    =>  '2021-04-01',
                'end_date'      =>  null,
            ],
            [
                'user_id'   =>  3,
                'start_date'    =>  '2010-01-01',
                'end_date'      =>  '2021-03-31',
            ],
            [
                'user_id'   =>  3,
                'start_date'    =>  '2021-04-01',
                'end_date'      =>  null,
            ],
            [
                'user_id'   =>  4,
                'start_date'    =>  '2010-01-01',
                'end_date'      =>  '2021-03-31',
            ],
            [
                'user_id'   =>  4,
                'start_date'    =>  '2021-04-01',
                'end_date'      =>  null,
            ],
            [
                'user_id'   =>  5,
                'start_date'    =>  '2010-01-01',
                'end_date'      =>  '2021-03-31',
            ],
            [
                'user_id'   =>  5,
                'start_date'    =>  '2021-04-01',
                'end_date'      =>  null,
            ],
        ];
        foreach ($data as $item) {
            $userSetting = new UserSetting;
            $userSetting->user_id = $item['user_id'];
            $userSetting->fill($item);
            $userSetting->save();
        }
    }
}
