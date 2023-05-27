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

Route::group(["as" => "auth.", "prefix" => "auth", "namespace" => "Auth"], function () {

	Route::group(["as" => "register.", "prefix" => "register"], function () {
		Route::get('/', 'RegisterController@index')->name('index');
		Route::post('/', 'RegisterController@post')->name('post');
	});

	Route::group(["as" => "login.", "prefix" => "login"], function () {
		Route::get('/', 'LoginController@index')->name('index');
		Route::post('/', 'LoginController@post')->name('post');
	});

	Route::get('/logout', 'LogoutController@logout')->name("logout");

	Route::group(["as" => "forgot-password.", "prefix" => "forgot-password"], function () {
		Route::get('/', 'ForgotPasswordController@index')->name('index');
		Route::post('/', 'ForgotPasswordController@post')->name('post');
	});

	Route::group(["as" => "reset-password.", "prefix" => "reset-password"], function () {
		Route::get('/', 'ResetPasswordController@index')->name('index');
		Route::post('/', 'ResetPasswordController@post')->name('post');
	});

	Route::group(["as" => "verification.", "prefix" => "verification"], function () {
		Route::get('verify', 'VerificationController@verificationNotice')->name("notice")->middleware('auth');
		Route::get('verify/{id}/{hash}', 'VerificationController@verifyUser')->name("verify")->middleware(['signed']);
		Route::post('verification-notification', 'VerificationController@verificationResend')->name("send")->middleware(['auth', 'throttle:6,1']);
	});
});

Route::group(['middleware' => ['auth', 'dashboard.access', 'verified:dashboard.auth.verification.notice']], function () {

	Route::impersonate();

	Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

	Route::get('/', 'DashboardController@index')->name('index');

	Route::get('notification', 'NotificationController@notification')->name('notification');
	Route::get('notification/read', 'NotificationController@notificationRead')->name('notification.read');
	Route::get('notification/markAsRead', 'NotificationController@markAsRead')->name('notification.markAsRead');

	Route::group(["as" => "profile.", "prefix" => "profile"], function () {
		Route::get('/', 'ProfileController@index')->name("index");
		Route::put('/', 'ProfileController@update')->name("update");
	});

	Route::group(["as" => "users.", "prefix" => "users"], function () {
		Route::get('/', 'UserController@index')->name("index");
		Route::get('/create', 'UserController@create')->name("create");
		Route::get('/{id}', 'UserController@show')->name("show");
		Route::get('/{id}/edit', 'UserController@edit')->name("edit");
		Route::post('/', 'UserController@store')->name("store");
		Route::put('/{id}', 'UserController@update')->name("update");
		Route::delete('/{id}', 'UserController@destroy')->name("destroy");
		Route::put('/{id}/restore', 'UserController@restore')->name("restore");
		Route::get('/{id}/impersonate', 'UserController@impersonate')->name("impersonate");
	});

	Route::group(["as" => "business-categories.", "prefix" => "business-categories"], function () {
		Route::get('/', 'BusinessCategoryController@index')->name("index");
		Route::post('/', 'BusinessCategoryController@store')->name("store");
		Route::put('/{id}', 'BusinessCategoryController@update')->name("update");
		Route::delete('/{id}', 'BusinessCategoryController@update')->name("destroy");
	});

	Route::group(["as" => "business.", "prefix" => "business"], function () {
		Route::get('/', 'BusinessController@index')->name("index");
		Route::get('/create', 'BusinessController@create')->name("create");
		Route::get('/{id}', 'BusinessController@show')->name("show");
		Route::get('/{id}/edit', 'BusinessController@edit')->name("edit");
		Route::post('/', 'BusinessController@store')->name("store");
		Route::put('/{id}', 'BusinessController@update')->name("update");
		Route::delete('/{id}', 'BusinessController@destroy')->name("destroy");
	});

	Route::group(["as" => "banks.", "prefix" => "banks"], function () {
		Route::get('/', 'BankController@index')->name("index");
		Route::post('/', 'BankController@store')->name("store");
		Route::put('/{id}', 'BankController@update')->name("update");
		Route::delete('/{id}', 'BankController@update')->name("destroy");
	});

	Route::group(["as" => "user-banks.", "prefix" => "user-banks"], function () {
		Route::get('/', 'UserBankController@index')->name("index");
		Route::get('/create', 'UserBankController@create')->name("create");
		Route::get('/{id}', 'UserBankController@show')->name("show");
		Route::get('/{id}/edit', 'UserBankController@edit')->name("edit");
		Route::post('/', 'UserBankController@store')->name("store");
		Route::put('/{id}', 'UserBankController@update')->name("update");
		Route::delete('/{id}', 'UserBankController@destroy')->name("destroy");
	});
});
