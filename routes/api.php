<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
    //return $request->user();
//});

Route::get('user/me.json', 'Api\UserController@show')->name('user.show');
Route::post('user/login', 'Api\UserController@login')->name('user.login');
Route::post('user/register', 'Api\UserController@register')->name('user.register');
Route::patch('user/edit', 'Api\UserController@edit')->name('user.edit');

Route::get('report.json', 'Api\ReportController@getAllReport')->name('report.getAllReport');
Route::get('report/{id}', 'Api\ReportController@getReportById')->name('report.getReportById');
Route::post('report', 'Api\ReportController@createReport')->name('report.create');
Route::patch('report', 'Api\ReportController@updateReport')->name('report.update');
Route::delete('report', 'Api\ReportController@deleteReport')->name('report.delete');

Route::post('image/report', 'Api\FileUploadController@storeImageReport')->name('report.storeImageReport');
Route::post('image/user', 'Api\FileUploadController@storeImageUser')->name('report.storeImageUser');