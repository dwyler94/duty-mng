<?php

namespace Database\Seeders;

use App\Constants\Roles;
use App\Models\EmploymentStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 1;
        $user = new User;
        $user->fill([
            'name' => 'admin001',
            'number' => 'admin001',
            'password' => Hash::make('admin001'),
            'role_id' => Roles::ADMIN,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => 26
        ]);
        $user->id = 26;
        $user->save();

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　札幌ひよこ',
            'number' => 'LK02',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　札幌白石本郷通園',
            'number' => 'LK03',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　長町園',
            'number' => 'LK66',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　泉中央園サイエンス+',
            'number' => 'LK07',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　銀杏町園',
            'number' => 'LK09',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　大野田園',
            'number' => 'LK10',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　富沢駅前園',
            'number' => 'LK50',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　中野栄園',
            'number' => 'LK12',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　小田原園サイエンス+',
            'number' => 'LK15',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => '通町ハピネス保育園',
            'number' => 'TM11',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　名取増田園',
            'number' => 'LK19',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　郡山安積園',
            'number' => 'LK45',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　松ノ木園',
            'number' => 'LK48',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　東砂ひよこ園',
            'number' => 'LK23',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　日本橋ひよこ園',
            'number' => 'LK26',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　天王寺ひよこ園',
            'number' => 'LK27',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　二島ひよこ園',
            'number' => 'LK29',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　佐真下園',
            'number' => 'LK31',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　真志喜園',
            'number' => 'LK32',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　古謝園',
            'number' => 'LK35',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　美里ひよこ園',
            'number' => 'LK38',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　宮里園',
            'number' => 'LK39',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　喜舎場ひよこ園',
            'number' => 'LK42',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　中城屋宜園',
            'number' => 'LK43',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

        $user = new User;
        $user->fill([
            'name' => 'もりのなかま保育園　安慶名園',
            'number' => 'LK44',
            'password' => Hash::make('pass'),
            'role_id' => Roles::USER_A,
            'employment_status_id' => EmploymentStatus::NORMAL,
            'office_id' => $i
        ]);
        $user->id = $i;
        $user->save();
        $i++;

    }
}
