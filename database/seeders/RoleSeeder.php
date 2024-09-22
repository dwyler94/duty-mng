<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use App\Constants\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permissions::LOGIN);
        $role->givePermissionTo(Permissions::STAMP);
        $role->givePermissionTo(Permissions::ATTENDANCE_STATUS_LIST);
        $role->givePermissionTo(Permissions::CHECK_ATTENDANCE_STATUS);
        $role->givePermissionTo(Permissions::ATTENDANCE_MONTHLY_STAT);
        $role->givePermissionTo(Permissions::SHIFT_PLAN);
        $role->givePermissionTo(Permissions::SHIFT_CREATE);

        $role->givePermissionTo(Permissions::ATTENDANCE_STAT);
        $role->givePermissionTo(Permissions::MASTER_SETTING);
        $role->givePermissionTo(Permissions::CONFIG_SETTING);

        $role = Role::create(['name' => 'エリアmgr']);
        $role->givePermissionTo(Permissions::LOGIN);
        $role->givePermissionTo(Permissions::STAMP);
        $role->givePermissionTo(Permissions::ATTENDANCE_STATUS_LIST);
        $role->givePermissionTo(Permissions::CHECK_ATTENDANCE_STATUS);
        $role->givePermissionTo(Permissions::ATTENDANCE_MONTHLY_STAT);
        $role->givePermissionTo(Permissions::SHIFT_PLAN);
        $role->givePermissionTo(Permissions::SHIFT_CREATE);

        $role->givePermissionTo(Permissions::ATTENDANCE_STAT);

        $role = Role::create(['name' => '事業所管理者']);
        $role->givePermissionTo(Permissions::LOGIN);
        $role->givePermissionTo(Permissions::STAMP);
        $role->givePermissionTo(Permissions::ATTENDANCE_STATUS_LIST);
        $role->givePermissionTo(Permissions::CHECK_ATTENDANCE_STATUS);
        $role->givePermissionTo(Permissions::ATTENDANCE_MONTHLY_STAT);
        $role->givePermissionTo(Permissions::SHIFT_PLAN);
        $role->givePermissionTo(Permissions::SHIFT_CREATE);

        $role = Role::create(['name' => '一版A']);
        $role->givePermissionTo(Permissions::LOGIN);
        $role->givePermissionTo(Permissions::STAMP);
        $role->givePermissionTo(Permissions::ATTENDANCE_STATUS_LIST);
        $role->givePermissionTo(Permissions::SHIFT_CREATE);

        $role->givePermissionTo(Permissions::ATTENDANCE_STAT);
        $role->givePermissionTo(Permissions::MASTER_SETTING);
        $role->givePermissionTo(Permissions::CONFIG_SETTING);

        $role = Role::create(['name' => '一版B']);
        $role->givePermissionTo(Permissions::LOGIN);
        $role->givePermissionTo(Permissions::STAMP);
        $role->givePermissionTo(Permissions::ATTENDANCE_STATUS_LIST);
    }
}
