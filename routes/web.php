<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CdrcusController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportCalController;
use App\Http\Controllers\SummaryController;

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

Route::get('/', function () {
    return view('welcome');
});

// 只看的到自己的行事曆
Route::get('calendar',[CalendarController::class,'index'])->middleware('phpbb3');
Route::resource('event', EventController::class)->middleware('phpbb3');
Route::resource('cdrcus', CdrcusController::class);

// 呼叫 /report_cal 頁
Route::get('report_cal', [ReportCalController::class,'index'])->middleware('phpbb3');
// api | 得到自己的單天行程 (在 /report_cal 頁使用): reportjs.blade.php
Route::get('report_cal_all', [ReportCalController::class ,'all'])->middleware('phpbb3');

// api | 得到自己的特休 (在 /calendar 頁使用): eventjs.blade.php
Route::get('pal', [PalController::class, 'index']);
// api | 得到全部門特休 (在 /report_cal 頁使用): reportjs.blade.php
Route::get('pal_all', [PalController::class , 'all']);

Route::get('report', [ReportController::class, 'index'])->name('report.index');
Route::post('report', [ReportController::class, 'show'])->middleware('phpbb3');
Route::get('getPdepno', [ReportController::class, 'getPdepno']); // api | 得到相關部門選擇 
Route::get('psr/{pdepno}', [ReportController::class ,'getPSRs']); // api | 依部門，得到相關的員工名字

Route::get('summary', [SummaryController::class, 'index'])->middleware('phpbb3')->name('summary.index');
Route::post('summary', [SummaryController::class, 'show'])->middleware('phpbb3');


/* 
Route::fallback(function (Request $request) {
    // return redirect('/');    // 回到首頁
    // return redirect()->route('calendar.index');  // 回到指定路徑頁面
});
*/