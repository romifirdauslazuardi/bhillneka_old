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

});
