<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\LoginController;
use App\Http\Controllers\Api\Admin\LogoutController;
use App\Http\Controllers\Api\Admin\PlaceController;
use App\Http\Controllers\Api\Admin\SliderController;
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
    });
});