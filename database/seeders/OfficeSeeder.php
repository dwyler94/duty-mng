<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\RestDeduction;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
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
//            [
//                'name'  =>  '沖縄本部',
//                'region_id'=>   4,
//                'industry_group_id' =>  1,
//                'office_group_id'   =>  3,
//                'rest_deduction_id' =>  RestDeduction::TYPE_2,
//                'type'          =>  Office::TYPE_OTHERS
//            ],
//            [
//                'name'  =>  '天王寺ひよこ園',
//                'region_id'=>   2,
//                'industry_group_id' =>  2,
//                'office_group_id'   =>  2,
//                'rest_deduction_id' =>  RestDeduction::TYPE_1,
//                'type'          =>  Office::TYPE_NURSERY
//            ],
//            [
//                'name'  =>  '日本橋ひよこ園',
//                'region_id'=>   2,
//                'industry_group_id' =>  2,
//                'office_group_id'   =>  2,
//                'rest_deduction_id' =>  RestDeduction::TYPE_2,
//                'type'          =>  Office::TYPE_NURSERY
//            ],
//            [
//                'name'  =>  '日本橋園',
//                'region_id'=>   2,
//                'industry_group_id' =>  2,
//                'office_group_id'   =>  2,
//                'rest_deduction_id' =>  RestDeduction::TYPE_1,
//                'type'          =>  Office::TYPE_NURSERY
//            ],
//            [
//                'name'  =>  '梅田あひる園',
//                'region_id'=>   2,
//                'industry_group_id' =>  2,
//                'office_group_id'   =>  2,
//                'rest_deduction_id' =>  RestDeduction::TYPE_1,
//                'type'          =>  Office::TYPE_NURSERY
//            ],
//            [
//                'name'  =>  '福岡かえる園',
//                'region_id'=>   3,
//                'industry_group_id' =>  2,
//                'office_group_id'   =>  3,
//                'rest_deduction_id' =>  RestDeduction::TYPE_2,
//                'type'          =>  Office::TYPE_NURSERY
//            ],
//            [
//                'name'  =>  '福岡すずめ園',
//                'region_id'=>   3,
//                'industry_group_id' =>  2,
//                'office_group_id'   =>  3,
//                'rest_deduction_id' =>  RestDeduction::TYPE_1,
//                'type'          =>  Office::TYPE_NURSERY
//            ],
//            [
//                'name'  =>  '新大阪支店',
//                'region_id'=>   2,
//                'industry_group_id' =>  1,
//                'office_group_id'   =>  2,
//                'rest_deduction_id' =>  RestDeduction::TYPE_2,
//                'type'          =>  Office::TYPE_OTHERS
//            ],
//            [
//                'name'  =>  '心斎橋支店',
//                'region_id'=>   2,
//                'industry_group_id' =>  1,
//                'office_group_id'   =>  2,
//                'rest_deduction_id' =>  RestDeduction::TYPE_1,
//                'type'          =>  Office::TYPE_OTHERS
//            ],
            // 初期データ-------------------------------------------------
            [
                'name'  =>  'もりのなかま保育園　札幌ひよこ',
                'region_id'=>   1,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  1,
                'number' => 'LK02'
            ],
            [
                'name'  =>  'もりのなかま保育園　札幌白石本郷通園',
                'region_id'=>   1,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  2,
                'number' => 'LK03'
            ],
            [
                'name'  =>  'もりのなかま保育園　長町園',
                'region_id'=>   2,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  3,
                'number' => 'LK66'
            ],
            [
                'name'  =>  'もりのなかま保育園　泉中央園サイエンス+',
                'region_id'=>   2,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  4,
                'number' => 'LK07'
            ],
            [
                'name'  =>  'もりのなかま保育園　銀杏町園',
                'region_id'=>   2,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  5,
                'number' => 'LK09'
            ],
            [
                'name'  =>  'もりのなかま保育園　大野田園',
                'region_id'=>   2,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  6,
                'number' => 'LK10'
            ],
            [
                'name'  =>  'もりのなかま保育園　富沢駅前園',
                'region_id'=>   2,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  7,
                'number' => 'LK50'
            ],
            [
                'name'  =>  'もりのなかま保育園　中野栄園',
                'region_id'=>   2,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  8,
                'number' => 'LK12'
            ],
            [
                'name'  =>  'もりのなかま保育園　小田原園サイエンス+',
                'region_id'=>   2,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  9,
                'number' => 'LK15'
            ],
            [
                'name'  =>  '通町ハピネス保育園',
                'region_id'=>   2,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  10,
                'number' => 'TM11'
            ],
            [
                'name'  =>  'もりのなかま保育園　名取増田園',
                'region_id'=>   2,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  11,
                'number' => 'LK19'
            ],
            [
                'name'  =>  'もりのなかま保育園　郡山安積園',
                'region_id'=>   3,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  12,
                'number' => 'LK45'
            ],
            [
                'name'  =>  'もりのなかま保育園　松ノ木園',
                'region_id'=>   4,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  13,
                'number' => 'LK48'
            ],
            [
                'name'  =>  'もりのなかま保育園　東砂ひよこ園',
                'region_id'=>   4,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  14,
                'number' => 'LK23'
            ],
            [
                'name'  =>  'もりのなかま保育園　日本橋ひよこ園',
                'region_id'=>   5,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  15,
                'number' => 'LK26'
            ],
            [
                'name'  =>  'もりのなかま保育園　天王寺ひよこ園',
                'region_id'=>   5,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  16,
                'number' => 'LK27'
            ],
            [
                'name'  =>  'もりのなかま保育園　二島ひよこ園',
                'region_id'=>   6,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  17,
                'number' => 'LK29'
            ],
            [
                'name'  =>  'もりのなかま保育園　佐真下園',
                'region_id'=>   7,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  18,
                'number' => 'LK31'
            ],
            [
                'name'  =>  'もりのなかま保育園　真志喜園',
                'region_id'=>   7,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  19,
                'number' => 'LK32'
            ],
            [
                'name'  =>  'もりのなかま保育園　古謝園',
                'region_id'=>   7,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  20,
                'number' => 'LK35'
            ],
            [
                'name'  =>  'もりのなかま保育園　美里ひよこ園',
                'region_id'=>   7,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  21,
                'number' => 'LK38'
            ],
            [
                'name'  =>  'もりのなかま保育園　宮里園',
                'region_id'=>   7,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  22,
                'number' => 'LK39'
            ],
            [
                'name'  =>  'もりのなかま保育園　喜舎場ひよこ園',
                'region_id'=>   7,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  23,
                'number' => 'LK42'
            ],
            [
                'name'  =>  'もりのなかま保育園　中城屋宜園',
                'region_id'=>   7,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  24,
                'number' => 'LK43'
            ],
            [
                'name'  =>  'もりのなかま保育園　安慶名園',
                'region_id'=>   7,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  2,
                'id' =>  25,
                'number' => 'LK44'
            ],
            [
                'name'  =>  'D/T管理用',
                'region_id'=>   NULL,
                'industry_group_id' =>  NULL,
                'office_group_id'   =>  NULL,
                'rest_deduction_id' =>  1,
                'type'          =>  1,
                'id' =>  26,
                'number' => NULL
            ],
        ];
        $i = 1;
        foreach($data as $item)
        {
            $t = new Office();
            $t->fill($item);
            $t->id = $i;
            $t->save();
            $i++;
        }
    }
}
