<?php

namespace Database\Seeders;

use App\Models\Child;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class ChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 1;
        // 初期データ------------------------------------------
        // もりのなかま保育園　札幌ひよこ
        Child::create([
            'id' => $i,
            'name' => '西川　大葵',
            'number' => 'LK02E0001',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 1,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child001@digital-think.jp',
            'admission_date' => '2020-08-01',
            'birthday' => '2020-03-17',
            'gender' => 1, //1:男 2:女
         ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '上村　彩華',
            'number' => 'LK02E0002',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 1,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child002@digital-think.jp',
            'admission_date' => '2020-09-28',
            'birthday' => '2019-07-29',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '富田　芽依',
            'number' => 'LK02E0003',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 1,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child003@digital-think.jp',
            'admission_date' => '2020-09-29',
            'birthday' => '2019-05-04',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '立花　優奈',
            'number' => 'LK02E0004',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 1,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child004@digital-think.jp',
            'admission_date' => '2021-04-19',
            'birthday' => '2019-07-19',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '横川　紗季',
            'number' => 'LK02E0005',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 1,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child005@digital-think.jp',
            'admission_date' => '2021-05-01',
            'birthday' => '2020-06-22',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '鈴木　蒼士',
            'number' => 'LK02E0006',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 1,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child006@digital-think.jp',
            'admission_date' => '2021-07-20',
            'birthday' => '2021-01-14',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '佐藤　俐來',
            'number' => 'LK02E0007',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 1,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child007@digital-think.jp',
            'admission_date' => '2021-10-01',
            'birthday' => '2020-04-16',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '須藤　悠乃',
            'number' => 'LK02E0008',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 1,
            'class_id' => 1,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child008@digital-think.jp',
            'admission_date' => '2022-02-21',
            'birthday' => '2021-04-22',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '須藤　久乃',
            'number' => 'LK02E0009',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 1,
            'class_id' => 1,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child009@digital-think.jp',
            'admission_date' => '2022-02-21',
            'birthday' => '2021-04-22',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;


        // もりのなかま保育園　札幌白石本郷通園
        Child::create([
            'id' => $i,
            'name' => '高石　晏凪',
            'number' => 'LK03E0001',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 2,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child010@digital-think.jp',
            'admission_date' => '2020-04-17',
            'birthday' => '2020-01-16',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '春日　雫',
            'number' => 'LK03E0002',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 2,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child011@digital-think.jp',
            'admission_date' => '2020-09-01',
            'birthday' => '2019-07-08',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '山口　凌生',
            'number' => 'LK03E0003',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 2,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child012@digital-think.jp',
            'admission_date' => '2021-10-01',
            'birthday' => '2019-10-10',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '瀧口　陽菜乃',
            'number' => 'LK03E0004',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 2,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child013@digital-think.jp',
            'admission_date' => '2021-08-02',
            'birthday' => '2020-08-14',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '藪　凛太',
            'number' => 'LK03E0005',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 2,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child014@digital-think.jp',
            'admission_date' => '2021-09-01',
            'birthday' => '2020-06-07',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '佐藤　日向子',
            'number' => 'LK03E0006',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 2,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child015@digital-think.jp',
            'admission_date' => '2021-11-01',
            'birthday' => '2020-04-10',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '伊藤　睦翔',
            'number' => 'LK03E0007',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 2,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child016@digital-think.jp',
            'admission_date' => '2021-12-01',
            'birthday' => '2020-12-07',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '前出　椛向',
            'number' => 'LK03E0008',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 2,
            'class_id' => 1,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child017@digital-think.jp',
            'admission_date' => '2021-09-01',
            'birthday' => '2021-06-13',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;


        // もりのなかま保育園　長町園
        Child::create([
            'id' => $i,
            'name' => '佐竹　碧羽',
            'number' => 'LK66E0001',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child018@digital-think.jp',
            'admission_date' => '2020-04-01',
            'birthday' => '2019-05-19',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '納所　怜杏',
            'number' => 'LK66E0002',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child019@digital-think.jp',
            'admission_date' => '2021-06-01',
            'birthday' => '2021-02-09',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '石森　咲奈',
            'number' => 'LK66E0003',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child020@digital-think.jp',
            'admission_date' => '2020-06-01',
            'birthday' => '2019-07-02',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '奥山　結衣',
            'number' => 'LK66E0004',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child021@digital-think.jp',
            'admission_date' => '2020-06-01',
            'birthday' => '2019-07-10',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '西村　依茉',
            'number' => 'LK66E0005',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child022@digital-think.jp',
            'admission_date' => '2021-04-15',
            'birthday' => '2021-01-14',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '慶伊　悠人',
            'number' => 'LK66E0006',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child023@digital-think.jp',
            'admission_date' => '2020-04-01',
            'birthday' => '2019-12-26',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '慶伊　凪人',
            'number' => 'LK66E0007',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 1,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child024@digital-think.jp',
            'admission_date' => '2022-04-01',
            'birthday' => '2021-12-31',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '齋藤　一桜',
            'number' => 'LK66E0008',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child025@digital-think.jp',
            'admission_date' => '2021-04-01',
            'birthday' => '2019-05-10',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '齋藤　一登',
            'number' => 'LK66E0009',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child026@digital-think.jp',
            'admission_date' => '2021-02-01',
            'birthday' => '2020-07-27',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '齋藤　陽月',
            'number' => 'LK66E0010',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 1,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child027@digital-think.jp',
            'admission_date' => '2022-01-24',
            'birthday' => '2021-10-23',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => '竹村　遙真',
            'number' => 'LK66E0011',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 3,
            'class_id' => 3,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child028@digital-think.jp',
            'admission_date' => '2020-04-20',
            'birthday' => '2020-01-19',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;

        for ($c = 1; $c < 100; $c++) {
            Child::create([
                'name' => 'テスト　園児' . sprintf('%05d', $c),
                'number' => 'LK50E' . sprintf('%05d', $c),
                'password' => Crypt::encryptString('pass'),
                'office_id' => 7,
                'class_id' => rand(1, 5),
                'qr' => 'LK-CHILDREN-' . Hash::make($i),
                'email' => 'child' .  sprintf('%05d', $c) . '@digital-think.jp',
                'admission_date' => '2020-04-02',
                'birthday' => '2022-04-02',
                'gender' => rand(1, 2), //1:男 2:女

            ]);
            $i++;
        }
        /*Child::create([
            'id' => $i,
            'name' => 'aaaaaa　bbbbbb',
            'number' => 'LK50E0001',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 7,
            'class_id' => 1,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child029@digital-think.jp',
            'admission_date' => '2020-04-20',
            'birthday' => '2022-04-02',
            'gender' => 2, //1:男 2:女

        ]);
        $i++;

        Child::create([
            'id' => $i,
            'name' => 'cccccc　dddddd',
            'number' => 'LK50E0002',
            'password' => Crypt::encryptString('pass'),
            'office_id' => 7,
            'class_id' => 2,
            'qr' => 'LK-CHILDREN-' . Hash::make($i),
            'email' => 'child030@digital-think.jp',
            'admission_date' => '2019-05-01',
            'birthday' => '2022-04-02',
            'gender' => 1, //1:男 2:女

        ]);
        $i++;*/
    }
}
