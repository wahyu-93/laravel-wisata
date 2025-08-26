<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\LoginController;
use App\Http\Controllers\Api\Admin\LogoutController;
use App\Http\Controllers\Api\Admin\PlaceController;
use App\Http\Controllers\Api\Admin\SliderController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Web\CategoryController as WebCategoryController;
use App\Http\Controllers\Api\Web\PlaceController as WebPlaceController;
use App\Http\Controllers\Api\Web\SliderController as WebSliderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('admin')->as('admin.')->group(function(){
    // login
    Route::post('/login', LoginController::class)->name('login');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/user', function(Request $request){
            return $request->user();
        })->name('user');

        Route::post('/logout', LogoutController::class)->name('logout');

        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::apiResource('/categories', CategoryController::class)->except(['create', 'edit']);
        Route::apiResource('/places', PlaceController::class)->except(['create', 'edit']);
        Route::apiResource('/sliders', SliderController::class)->except(['create', 'edit', 'show', 'update']);
        Route::apiResource('/users', UserController::class)->except(['create', 'edit']);
    });
});

Route::prefix('web')->as('web.')->group(function(){
    Route::get('/categories', [WebCategoryController::class, 'index'])->name('web.categories.index');
    Route::get('/categories/{slug}', [WebCategoryController::class, 'show'])->name('web.categories.show');

    Route::get('/places', [WebPlaceController::class, 'index'])->name('web.place.index');
    Route::get('/places/{slug}', [WebPlaceController::class, 'show'])->name('web.place.show');
    Route::get('/all-place', [WebPlaceController::class, 'allPlaces'])->name('web.place.allPlaces');

    Route::get('/sliders', [WebSliderController::class, 'index'])->name('web.sliders.index');
});