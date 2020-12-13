<?php

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

Route::get('/', function () {
    return view('home');
});
Route::middleware(['auth'])->group(function() {


//    Section Route
    Route::get('/section','SectionController@index')->name('section.index');
    Route::get('/section/create','SectionController@create')->name('section.create');
    Route::post('/section','SectionController@store')->name('section.store');
    Route::get('/section/{section}','SectionController@edit')->name('section.edit');
    Route::patch('/section/{section}','SectionController@update')->name('section.update');
    Route::delete('/section/{section}','SectionController@destroy')->name('section.destroy');
    //    Student route
    Route::get('/students','UserController@index')->name('user.index');
    Route::delete('/students/{student}','UserController@destroy')->name('student.destroy');
//    Search Route
    Route::post('/search','SearchController@find')->name('search.find');

//    Attendance route
    Route::get('user/{user}/attend', 'AttendanceController@attend')->name('attendance.attend');
    Route::get('/attendances','AttendanceController@index')->name('attendance.index');
    Route::post('/attendance/filter','AttendanceController@filter')->name('attendance.filter');
//    Route::post('/attendance/filter')

//    Report Route
    Route::get('/report/attendance', 'ReportController@attendance')->name('report.attendance');
});
Route::get('/dashboard','DashboardController@index')->name('dashboard');

Auth::routes();

