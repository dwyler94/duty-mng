<?php

use App\Http\Controllers\API\V1\Child\AttendanceController;
use App\Http\Controllers\API\V1\Child\ChildcareDiaryController;
use App\Http\Controllers\API\V1\Child\ContactBookController;
use App\Http\Controllers\API\V1\ChildApplicationTableController;
use App\Http\Controllers\API\V1\ShiftController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\WorkTotalController;
use App\Http\Controllers\ChildcareController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return redirect('/home');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/{vue_capture?}', function () {
//     return view('home');
// })->where('vue_capture', '[\/\w\.-]*');
// ->middleware('auth');
Route::get('/shift/csv/{office}', [ShiftController::class, 'csv']);
Route::get('/user/csv', [UserController::class, 'csv']);
Route::get('/work-total/csv', [WorkTotalController::class, 'csv'])->name('work_total.csv.get');
Route::get("/test", [TestController::class, 'test']);
Route::get('/individual-summary/excel', [WorkTotalController::class, 'exportIndividual']);
Route::get("/notice/{id}", [NoticeController::class, 'notice']);
Route::get("/notice-finish/{id}/{date}/{bool}", [NoticeController::class, 'noticeFinish']);

Route::get('/child-monthly-attendance/csv/{child}', [AttendanceController::class, 'monthlyListCsv']);
Route::get('/child-monthly-attendance/monthly/excel/{office}', [AttendanceController::class, 'excel']);
Route::get('/childcare-contact-book/excel/{child}', [ContactBookController::class, 'excel']);
Route::get('/childcare-diary/excel', [ChildcareDiaryController::class, 'excel']);
Route::get('/childcare-application-table/excel/{office}', [ChildApplicationTableController::class, 'exportExcel']);

// Route::get('/child', [ChildcareController::class, 'index']);
Route::get('/child/{path?}', [ChildcareController::class, 'index'])
    ->where('path', '.*');
// Route::get('/childcare', [ChildcareController::class, 'index']);
Route::view('/{path?}', 'home')
    ->where('path', '.*');
