<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(["as" => "indonesia.", "prefix" => "indonesia"], function () {

	Route::get('/province', 'IndonesiaController@province')->name('province');
    Route::get('/city', 'IndonesiaController@city')->name('city');
    Route::get('/district', 'IndonesiaController@district')->name('district');
    Route::get('/village', 'IndonesiaController@village')->name('village');
    
});
