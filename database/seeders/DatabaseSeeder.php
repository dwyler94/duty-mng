<?php

namespace Database\Seeders;

use App\Models\ChildInformation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RegionSeeder::class,
            IndustryGroupSeeder::class,
            OfficeGroupSeeder::class,
            EmploymentStatusSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            OfficeSeeder::class,
            UserSeeder::class,
            DeviceSeeder::class,

            UserSettingSeeder::class,
            YearSeeder::class,
            ScheduledWorkingSeeder::class,
            ReasonForVacationSeeder::class,
            ApplicationClassSeeder::class,
            OfficeScheduledWorkingSeeder::class,
            RestDeductionSeeder::class,

            SettingSeeder::class,
            CodeSeeder::class,

            ChildrenClassSeeder::class,
            ChildTypesSeeder::class,
            ChildMoodTypeSeeder::class,
            ReasonForAbsenceSeeder::class,
            ChildSeeder::class,
            ChildInformationSeeder::class,
            BusinessTypeSeeder::class,
            OfficeInformationSeeder::class,
            ChildrenAttendenceSeeder::class,

            EraNamesSeeder::class,

            // dependency
            ChildrenClassPeriodSeeder::class, // dependent on ChildSeeder
            ContactBookTypePeriodSeeder::class, // dependent on ChildSeeder
        ]);
    }
}
