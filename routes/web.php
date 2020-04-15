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

Route::get('/clients', function () {
    return view('auth/register');
});

Route::resource('person', 'PersonController')->names('person');
Route::resource('user', 'UserController')->names('user');
Route::resource('history', 'HistoryController')->names('history');
Route::resource('call', 'CallController')->names('call');
Route::resource('verification', 'VerificationController')->names('verification');
Route::resource('file', 'FileController')->names('file');

Route::resource('task', 'TaskController')->names('task');

Route::get('/drivers', 'PersonController@drivers');
Route::get('/unique_phone', 'PersonController@unique_phone');
Route::post('/statistic', 'PersonController@statistic');

Auth::routes();

Route::get('logout', 'Auth\LoginController@logout');

Route::get('{any}', function () {
    return view('welcome');
 })->where('any', '.*');