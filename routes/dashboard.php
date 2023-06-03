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

	Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);

	Route::get('/', 'DashboardController@index')->name('index')->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::USER, RoleEnum::ADMIN_AGEN])]);

	Route::get('notification', 'NotificationController@notification')->name('notification')->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::USER])]);
	Route::get('notification/read', 'NotificationController@notificationRead')->name('notification.read')->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::USER])]);
	Route::get('notification/markAsRead', 'NotificationController@markAsRead')->name('notification.markAsRead')->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::USER])]);

	Route::group(["as" => "profile.", "prefix" => "profile"], function () {
		Route::get('/', 'ProfileController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::USER])]);
		Route::put('/', 'ProfileController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::USER])]);
	});

	Route::group(["as" => "users.", "prefix" => "users"], function () {
		Route::get('/', 'UserController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/create', 'UserController@create')->name("create")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/{id}', 'UserController@show')->name("show")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/{id}/edit', 'UserController@edit')->name("edit")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::post('/', 'UserController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::put('/{id}', 'UserController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::delete('/{id}', 'UserController@destroy')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::put('/{id}/restore', 'UserController@restore')->name("restore")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::get('/{id}/impersonate', 'UserController@impersonate')->name("impersonate")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
	});

	Route::group(["as" => "business-categories.", "prefix" => "business-categories"], function () {
		Route::get('/', 'BusinessCategoryController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::post('/', 'BusinessCategoryController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::put('/{id}', 'BusinessCategoryController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::delete('/{id}', 'BusinessCategoryController@update')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
	});

	Route::group(["as" => "business.", "prefix" => "business"], function () {
		Route::get('/', 'BusinessController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/create', 'BusinessController@create')->name("create")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/{id}', 'BusinessController@show')->name("show")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/{id}/edit', 'BusinessController@edit')->name("edit")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::post('/', 'BusinessController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::put('/{id}', 'BusinessController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::delete('/{id}', 'BusinessController@destroy')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
	});

	Route::group(["as" => "banks.", "prefix" => "banks"], function () {
		Route::get('/', 'BankController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::post('/', 'BankController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::put('/{id}', 'BankController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::delete('/{id}', 'BankController@update')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
	});

	Route::group(["as" => "user-banks.", "prefix" => "user-banks"], function () {
		Route::get('/', 'UserBankController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/create', 'UserBankController@create')->name("create")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/{id}', 'UserBankController@show')->name("show")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/{id}/edit', 'UserBankController@edit')->name("edit")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::post('/', 'UserBankController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::put('/{id}', 'UserBankController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::delete('/{id}', 'UserBankController@destroy')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
	});

	Route::group(["as" => "units.", "prefix" => "units"], function () {
		Route::get('/', 'UnitController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::post('/', 'UnitController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::put('/{id}', 'UnitController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::delete('/{id}', 'UnitController@update')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
	});

	Route::group(["as" => "product-categories.", "prefix" => "product-categories"], function () {
		Route::get('/', 'ProductCategoryController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::post('/', 'ProductCategoryController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::put('/{id}', 'ProductCategoryController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::delete('/{id}', 'ProductCategoryController@update')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
	});

	Route::group(["as" => "products.", "prefix" => "products"], function () {
		Route::get('/', 'ProductController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/create', 'ProductController@create')->name("create")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/{id}', 'ProductController@show')->name("show")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/{id}/edit', 'ProductController@edit')->name("edit")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::post('/', 'ProductController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::put('/{id}', 'ProductController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::delete('/{id}', 'ProductController@destroy')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);

		Route::group(["as" => "stocks.", "prefix" => "stocks"], function () {
			Route::post('/', 'ProductStockController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
			Route::put('/{id}', 'ProductStockController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
			Route::delete('/{id}', 'ProductStockController@destroy')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		});
	});

	Route::group(["as" => "settings.", "prefix" => "settings","namespace" => "Setting"], function () {
		Route::group(["as" => "dashboard.", "prefix" => "dashboard"], function () {
			Route::get('/', 'DashboardSettingController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
			Route::put('/', 'DashboardSettingController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		});

		Route::group(["as" => "fee.", "prefix" => "fee"], function () {
			Route::get('/', 'SettingFeeController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
			Route::put('/', 'SettingFeeController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		});
	});

	Route::group(["as" => "providers.", "prefix" => "providers"], function () {
		Route::get('/', 'ProviderController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::get('/create', 'ProviderController@create')->name("create")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::get('/{id}', 'ProviderController@show')->name("show")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::get('/{id}/edit', 'ProviderController@edit')->name("edit")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::post('/', 'ProviderController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::put('/{id}', 'ProviderController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::delete('/{id}', 'ProviderController@destroy')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
	});

	Route::group(["as" => "orders.", "prefix" => "orders"], function () {
		Route::get('/', 'OrderController@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN, RoleEnum::USER])]);
		Route::get('/create', 'OrderController@create')->name("create")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::get('/{id}', 'OrderController@show')->name("show")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN, RoleEnum::USER])]);
		Route::get('/{id}/edit', 'OrderController@edit')->name("edit")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::post('/', 'OrderController@store')->name("store")->middleware(['role:' . implode('|', [RoleEnum::OWNER, RoleEnum::AGEN, RoleEnum::ADMIN_AGEN])]);
		Route::put('/{id}', 'OrderController@update')->name("update")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
		Route::delete('/{id}', 'OrderController@destroy')->name("destroy")->middleware(['role:' . implode('|', [RoleEnum::OWNER])]);
	});
});
