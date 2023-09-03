<?php

use App\Enums\RoleEnum;
use App\Models\AboutSetting;
use App\Traits\HasSeo;
use App\Models\Business;
use App\Models\LandingAgen;
use Illuminate\Support\Str;
use App\Services\FaqService;
use App\Services\WhyUsService;
use App\Models\BusinessCategory;
use App\Models\HeaderSetting;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\TestimoniSetting;
use App\Services\PartnerService;
use App\Services\DashboardService;
use App\Services\OurServiceService;
use App\Services\TestimonialService;

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
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

$business_category = BusinessCategory::with('template')->get();

foreach ($business_category as $item) {
    $slug = Str::slug($item->name);
    Route::group(['as' => $slug . ".", "prefix" => "/" . $slug], function () use ($item) {
        Route::get('/', function () {
            return redirect()->to('/');
        });
        $business = Business::where('category_id', $item->id)->get();
        $view = $item->template?->slug;
        foreach ($business as $bus) {
            Route::group(['as' => $bus->slug . '.', 'prefix' => '/' . $bus->slug], function () use ($bus, $view) {
                Route::get('/', function (Request $request) use ($bus, $view) {
                    $business_id = $bus->id;
                    $landing = LandingAgen::where('business_id', $business_id)->first();
                    $headerSection = HeaderSetting::where('business_id', $business_id)->get();
                    $testimoniSection = TestimoniSetting::where('business_id', $business_id)->get();
                    $aboutSection = AboutSetting::where('business_id', $business_id)->first();
                    $product = Product::where('business_id', $business_id)->with('category')->get();
                    $categoryProduct = ProductCategory::where('business_id', $business_id)->with('products')->get();
                    $data = [
                        'data' => $landing,
                        'business' => $bus,
                        'headerSection' => $headerSection,
                        'testimoniSection' => $testimoniSection,
                        'aboutSection' => $aboutSection,
                        'product' => $product,
                        'categoryProduct' => $categoryProduct->sortBy(function ($categoryProduct) {
                            return $categoryProduct->products->count();
                        }, SORT_REGULAR, true)->values(),
                    ];
                    return view("templates." . $view, $data);
                });
            });
        }
    });
}

Route::group(['middleware' => ['auth', 'dashboard.access', 'verified:dashboard.auth.verification.notice']], function () use ($business_category) {
    foreach ($business_category as $item) {
        Route::group(["as" => "landing-page-agen.", "prefix" => "dashboard/landing-page-agen", "namespace" => "Dashboard"], function () use ($item) {
            $template = Str::slug($item->template?->name);
            Route::group(["as" => $template . ".", "prefix" => $template], function () use ($template) {
                Route::get('/', ucfirst($template) . 'Controller@index')->name("index")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
                Route::post('/about', ucfirst($template) . 'Controller@about')->name("about")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
                Route::post('/testimoni', ucfirst($template) . 'Controller@testimoni')->name("testimoni")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
                Route::get('/testimoni/{id}', ucfirst($template) . 'Controller@testimoniShow')->name("testimoniShow")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
                Route::put('/testimoni/{id}', ucfirst($template) . 'Controller@testimoniUpdate')->name("testimoniUpdate")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
                Route::delete('/testimoni/{id}', ucfirst($template) . 'Controller@testimoniDelete')->name("testimoniDelete")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
                Route::post('/header', ucfirst($template) . 'Controller@header')->name("header")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
                Route::get('/header/{id}', ucfirst($template) . 'Controller@headerShow')->name("headerShow")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
                Route::put('/header/{id}', ucfirst($template) . 'Controller@headerUpdate')->name("headerUpdate")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
                Route::delete('/header/{id}', ucfirst($template) . 'Controller@headerDelete')->name("headerDelete")->middleware(['role:' . implode('|', [RoleEnum::AGEN, RoleEnum::OWNER])]);
            });
        });
    }
});

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
