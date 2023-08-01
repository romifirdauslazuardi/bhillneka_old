<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route("landing-page.home.index");
});

Route::group(["as" => "home.", "prefix" => "/"], function () {
    Route::get('/', 'HomeController@index')->name('index');
});

Route::group(["as" => "contact-us.", "prefix" => "contact-us"], function () {
    Route::get('/', 'ContactUsController@index')->name('index');
    Route::post('/', 'ContactUsController@store')->name('store');
});

Route::group(["as" => "our-services.", "prefix" => "our-services"], function () {
    Route::get('/', 'OurServiceController@index')->name('index');
});

Route::group(["as" => "pages.", "prefix" => "pages"], function () {
    Route::get('/{slug}', 'PageController@index')->name('index');
});

Route::group(["as" => "faqs.", "prefix" => "faqs"], function () {
    Route::get('/', 'FaqController@index')->name('index');
});

Route::group(["as" => "manual-payments.", "prefix" => "manual-payments"], function () {
    Route::get('/{code}', 'ManualPaymentController@index')->name('index');
    Route::put('/{id}', 'ManualPaymentController@proofOrder')->name('proofOrder');
});

Route::group(["as" => "orders.", "prefix" => "orders"], function () {
    Route::get('/', 'OrderController@index')->name('index');
    Route::put('/{id}/updateProvider', 'OrderController@updateProvider')->name('updateProvider');
});

Route::group(["as" => "buy-products.", "prefix" => "buy-products"], function () {
    Route::get('/{slug}', 'BuyProductController@index')->name('index');
    Route::post('/', 'BuyProductController@store')->name('store');
});

Route::group(["as" => "shops.", "prefix" => "shops"], function () {
    Route::get('/{business_slug}', 'ShopController@index')->name('index');
    Route::post('/addToCart', 'ShopController@addToCart')->name('addToCart');
    Route::put('/updateCart/{id}', 'ShopController@updateCart')->name('updateCart');
    Route::delete('/deleteCart/{id}', 'ShopController@deleteCart')->name('deleteCart');
    Route::post('/clearCart', 'ShopController@clearCart')->name('clearCart');
});
