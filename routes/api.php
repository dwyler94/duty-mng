<?php

use App\Http\Controllers\API\V1\ApplicationController;
use App\Http\Controllers\API\V1\AttendanceStatusController;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\ChildApplicationTableController;
use App\Http\Controllers\API\V1\ConstantController;
use App\Http\Controllers\API\V1\HourlyWageController;
use App\Http\Controllers\API\V1\MonthlySummaryController;
use App\Http\Controllers\API\V1\OfficeController;
use App\Http\Controllers\API\V1\RegionController;
use App\Http\Controllers\API\V1\SettingController;
use App\Http\Controllers\API\V1\ShiftController;
use App\Http\Controllers\API\V1\StampController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\VacationReasonController;
use App\Http\Controllers\API\V1\WorkingHourController;
use App\Http\Controllers\API\V1\WorkStatusController;
use App\Http\Controllers\API\V1\WorkTotalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('version', function () {
    return response()->json(['version' => config('app.version')]);
});

Route::namespace('App\\Http\\Controllers\\API\V1')->prefix('/v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/constants', [ConstantController::class, 'get']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/office/user-capable', [OfficeController::class, 'getUserCabableOffices']);
        Route::get('/office/{office}/scheduled-working-month', [OfficeController::class, 'getScheduledWorkingMonth']);

        Route::post('/shift/{office}/copy', [ShiftController::class, 'copy'])->name('shift.copy_by_office.csv');
        Route::get('/shift/{office}/csv', [ShiftController::class, 'csv'])->name('shift.get_by_office.csv');
        Route::post('/shift/{office}', [ShiftController::class, 'save'])->name('shift.create');
        Route::get('/shift/{office}', [ShiftController::class, 'get'])->name('shift.get_by_office');
        Route::delete('/shift/{shift}', [ShiftController::class, 'delete'])->name('shift.delete');
        Route::get('/childcare-detail/{office}', [ShiftController::class, 'getChildcareSchedule'])->name('shift.childcare_schedule');
        Route::get('/childcare-application-table/{office}', [ChildApplicationTableController::class, 'get']);

        Route::get('/working-hours/user/{user}', [WorkingHourController::class, 'getWorkingHourWithUser'])->name('working_hour.user_cabable.get');

        Route::get('/reason-for-vacation/enable', [VacationReasonController::class, 'getEnable'])->name('vacation_reason.get_enable');

        Route::get('/stamp/status', [StampController::class, 'status'])->name('stamp.status');
        Route::post('/stamp/commute', [StampController::class, 'commute'])->name('stamp.commute');
        Route::post('/stamp/leave', [StampController::class, 'leave'])->name('stamp.leave');

        Route::get('/attend/{office}', [WorkStatusController::class, 'get'])->name('attend.work_status');
        Route::post('/attend', [WorkStatusController::class, 'create'])->name('attend.work_status.create');
        Route::put('/attend/{attendance}', [WorkStatusController::class, 'update'])->name('attend.work_status.update');

        Route::get('/attendance/status', [AttendanceStatusController::class, 'index'])->name('attendance.status.index');
        Route::post('/application', [ApplicationController::class, 'create'])->name('application.create');
        Route::put('/application/approve/{application}', [ApplicationController::class, 'approve'])->name('application.approve');
        Route::put('/application/reject/{application}', [ApplicationController::class, 'reject'])->name('application.reject');
        Route::put('/application/{application}', [ApplicationController::class, 'update'])->name('application.update');

        Route::put('/monthly-summary/attendances/approve', [MonthlySummaryController::class, 'approve'])->name('monthly_summary.attendance.approve');
        Route::post('/monthly-summary/attendances', [MonthlySummaryController::class, 'saveAttendance'])->name('monthly_summary.attendance.save');

        Route::get('/monthly-summary/{user}', [MonthlySummaryController::class, 'get'])->name('monthly_summary.get');
        Route::get('/work-total', [WorkTotalController::class, 'get'])->name('work_total.get');
        Route::get('/work-total/csv-available', [WorkTotalController::class, 'csvAvailable'])->name('work_total.csvavailable');
        Route::get('/office/{office}/users', [UserController::class, 'getUsers'])->name('office.users');

        Route::middleware(['can:admin-only'])->group(function () {
            Route::get('/office-master', [OfficeController::class, 'get'])->name('office.get');
            Route::post('/office-master', [OfficeController::class, 'create'])->name('office_master.create');
            Route::put('/office-master/{office}', [OfficeController::class, 'update'])->name('office_master.update');
            Route::delete('/office-master/{office}', [OfficeController::class, 'delete'])->name('office_master.delete');

            Route::get('/office-master-nursery', [OfficeController::class, 'getNurseryOffices'])->name('office.get.nursery');

            Route::get('/office-master/{office}/scheduled-working', [OfficeController::class, 'getScheduleWorkings'])->name('office_master.scheduled_working.get');
            Route::post('/office-master/{office}/scheduled-working', [OfficeController::class, 'saveScheduleWorking'])->name('office_master.scheduled_working.save');


            Route::get('/region', [RegionController::class, 'get'])->name('region.get');
            Route::post('/region', [RegionController::class, 'create'])->name('region.create');
            Route::post('/region/{region}', [RegionController::class, 'update'])->name('region.update');
            Route::delete('/region/{region}', [RegionController::class, 'delete'])->name('region.delete');

            Route::get('/reason-for-vacation', [VacationReasonController::class, 'get'])->name('vacation_reason.get');
            Route::post('/reason-for-vacation', [VacationReasonController::class, 'create'])->name('vacation_reason.create');
            Route::put('/reason-for-vacation/status/{reasonForVacation}', [VacationReasonController::class, 'updateStatus']);
            Route::post('/reason-for-vacation/{reasonForVacation}', [VacationReasonController::class, 'update']);
            Route::delete('/reason-for-vacation/{reasonForVacation}', [VacationReasonController::class, 'delete']);

            Route::get('/working-hours', [WorkingHourController::class, 'get']);
            Route::post('/working-hours', [WorkingHourController::class, 'create']);
            Route::put('/working-hours/status/{workingHour}', [WorkingHourController::class, 'updateStatus']);
            Route::put('/working-hours/{workingHour}', [WorkingHourController::class, 'update']);
            Route::delete('/working-hours/{workingHour}', [WorkingHourController::class, 'delete']);

            Route::get('/setting', [SettingController::class, 'get']);
            Route::post('/setting', [SettingController::class, 'save']);

            Route::get('/hourly-wage', [HourlyWageController::class, 'get']);
            Route::post('/hourly-wage', [HourlyWageController::class, 'create']);
            Route::put('/hourly-wage/{hourlyWage}', [HourlyWageController::class, 'update']);
            Route::delete('/hourly-wage/{hourlyWage}', [HourlyWageController::class, 'delete']);

            Route::get('/users', [UserController::class, 'get']);
            Route::put('/users/{user}/setting', [UserController::class, 'updateSetting']);
            Route::put('/users/{user}/role', [UserController::class, 'updateRole']);

            Route::post('/users', [UserController::class, 'create']);
            Route::put('/users/{user}', [UserController::class, 'update']);
            Route::delete('/users/{user}', [UserController::class, 'delete']);
        });
    });
});
