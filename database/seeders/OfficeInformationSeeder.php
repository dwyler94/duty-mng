<?php

namespace Database\Seeders;

use App\Models\OfficeInformation;
use Illuminate\Database\Seeder;

class OfficeInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期データ------------------------------------------
        // もりのなかま保育園　札幌ひよこ
        OfficeInformation::create([
            'office_id' => 1,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　札幌白石本郷通園
        OfficeInformation::create([
            'office_id' => 2,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　長町園
        OfficeInformation::create([
            'office_id' => 3,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　泉中央園サイエンス+
        OfficeInformation::create([
            'office_id' => 4,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　銀杏町園
        OfficeInformation::create([
            'office_id' => 5,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　大野田園
        OfficeInformation::create([
            'office_id' => 6,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　富沢駅前園
        OfficeInformation::create([
            'office_id' => 7,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'open_time_short' => '8:30',
            'close_time_short' => '16:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  3,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　中野栄園
        OfficeInformation::create([
            'office_id' => 8,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　小田原園サイエンス+
        OfficeInformation::create([
            'office_id' => 9,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // 通町ハピネス保育園
        OfficeInformation::create([
            'office_id' => 10,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:00',
            'close_time' => '18:00',
            'open_time_short' => '9:00',
            'close_time_short' => '17:00',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  2,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　名取増田園
        OfficeInformation::create([
            'office_id' => 11,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　郡山安積園
        OfficeInformation::create([
            'office_id' => 12,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'open_time_short' => '8:30',
            'close_time_short' => '16:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  2,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　松ノ木園
        OfficeInformation::create([
            'office_id' => 13,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'open_time_short' => '8:30',
            'close_time_short' => '16:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  2,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　東砂ひよこ園
        OfficeInformation::create([
            'office_id' => 14,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　日本橋ひよこ園
        OfficeInformation::create([
            'office_id' => 15,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　天王寺ひよこ園
        OfficeInformation::create([
            'office_id' => 16,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　二島ひよこ園
        OfficeInformation::create([
            'office_id' => 17,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　佐真下園
        OfficeInformation::create([
            'office_id' => 18,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　真志喜園
        OfficeInformation::create([
            'office_id' => 19,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　古謝園
        OfficeInformation::create([
            'office_id' => 20,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　美里ひよこ園
        OfficeInformation::create([
            'office_id' => 21,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　宮里園
        OfficeInformation::create([
            'office_id' => 22,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　喜舎場ひよこ園
        OfficeInformation::create([
            'office_id' => 23,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　中城屋宜園
        OfficeInformation::create([
            'office_id' => 24,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);

        // もりのなかま保育園　安慶名園
        OfficeInformation::create([
            'office_id' => 25,
            'start_date' => '2022-03-28',
            'end_date' => NULL,
            'open_time' => '7:30',
            'close_time' => '18:30',
            'capacity' => NULL,
            'appropriate_number_0' => NULL,
            'appropriate_number_1' => NULL,
            'appropriate_number_2' => NULL,
            'appropriate_number_3' => NULL,
            'appropriate_number_4' => NULL,
            'appropriate_number_5' => NULL,
            'business_type_id' =>  1,//1:企業主導型保育事業所 2:認可保育所 3:小規模保育事業所 4:放課後等デイサービス / 児童発達支援事業所
        ]);
    }
}
