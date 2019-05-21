<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/download/{uuid}/name/{name}', 'HomeController@download');


Route::prefix('student')->group(function() {
    Route::get('/home', 'HomeController@index')->name('student.home');
});

Route::prefix('leader')->group(function() {
    Route::get('/home', 'HomeController@leadIndex')->name('leader.home');
});

Route::prefix('examiner')->group(function() {
    Route::get('/home', 'HomeController@index')->name('examiner.home');
});

Route::prefix('admin')->group(function() {
    Route::get('/home', 'HomeController@index')->name('admin.home');
});