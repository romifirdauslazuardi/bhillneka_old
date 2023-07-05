<?php

use Illuminate\Support\Facades\Route;
use App\Enums\RoleEnum;

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

Route::group(['middleware' => ['auth', 'dashboard.access', 'verified:dashboard.auth.verification.notice']], function () {
    Route::group(["as" => "indonesia.", "prefix" => "indonesia"], function () {

        Route::get('/province', 'IndonesiaController@province')->name('province');
        Route::get('/city', 'IndonesiaController@city')->name('city');
        Route::get('/district', 'IndonesiaController@district')->name('district');
        Route::get('/village', 'IndonesiaController@village')->name('village');
    
    });

    Route::group(["as" => "business.", "prefix" => "business"], function () {
        Route::get('/', 'BusinessController@index')->name('index');
    });
    
    Route::group(["as" => "units.", "prefix" => "units"], function () {
        Route::get('/', 'UnitController@index')->name('index');
    });
    
    Route::group(["as" => "products.", "prefix" => "products"], function () {
        Route::get('/', 'ProductController@index')->name('index');
        Route::get('/search', 'ProductController@showByCode')->name('showByCode');
    });
    
    Route::group(["as" => "customers.", "prefix" => "customers"], function () {
        Route::get('/', 'CustomerController@index')->name('index');
    });

    Route::group(["as" => "orders.", "prefix" => "orders"], function () {
        Route::get('/', 'OrderController@index')->name('index');
    });

    Route::group(["as" => "mikrotik-configs.", "prefix" => "mikrotik-configs"], function () {
        Route::get('/profile/pppoe', 'MikrotikConfigController@profilePppoe')->name('profilePppoe');
        Route::get('/profile/hotspot', 'MikrotikConfigController@profileHotspot')->name('profileHotspot');
        Route::get('/server/hotspot', 'MikrotikConfigController@serverHotspot')->name('serverHotspot');
    });

    Route::group(["as" => "tables.", "prefix" => "tables"], function () {
        Route::get('/', 'TableController@index')->name('index');
    });
});