<?php

use App\Http\Controllers\Api\Admin\LoginController;
use App\Http\Controllers\Api\Admin\LogoutController;
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

Route::prefix('admin')->group(function(){
    // login
    Route::post('/login', LoginController::class)->name('admin.login');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/user', function(Request $request){
            return $request->user();
        })->name('user');

         Route::post('/logout', LogoutController::class)->name('admin.logout');
    });
});