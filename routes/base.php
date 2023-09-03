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

    Route::group(["as" => "users.", "prefix" => "users"], function () {
        Route::get('/customer', 'UserController@customer')->name('customer');
        Route::get('/owner', 'UserController@owner')->name('owner');
        Route::get('/agen', 'UserController@agen')->name('agen');
        Route::get('/adminAgen', 'UserController@adminAgen')->name('adminAgen');
    });

    Route::group(["as" => "orders.", "prefix" => "orders"], function () {
        Route::get('/', 'OrderController@index')->name('index');
        Route::get('/customerGeneral', 'OrderController@customerGeneral')->name('customerGeneral');
    });

    Route::group(["as" => "mikrotik-configs.", "prefix" => "mikrotik-configs"], function () {
        Route::get('/profile/pppoe/{mikrotik_id}', 'MikrotikConfigController@profilePppoe')->name('profilePppoe');
        Route::get('/profile/hotspot/{mikrotik_id}', 'MikrotikConfigController@profileHotspot')->name('profileHotspot');
        Route::get('/server/hotspot/{mikrotik_id}', 'MikrotikConfigController@serverHotspot')->name('serverHotspot');
        Route::get('/profile/pppoe/{mikrotik_id}/{name}', 'MikrotikConfigController@detailProfilePppoe')->name('detailProfilePppoe');
    });

    Route::group(["as" => "tables.", "prefix" => "tables"], function () {
        Route::get('/', 'TableController@index')->name('index');
    });
});
