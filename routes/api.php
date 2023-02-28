<?php

use App\Http\Controllers\Admin\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\API\OrderApiControllers;

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

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', [ AuthController::class, 'login' ]);
//    Route::post('registration', [ AuthController::class, 'login' ]);
//    Route::post('logout', [ AuthController::class, 'login' ]);
    Route::post('refresh', [ AuthController::class, 'refresh' ]);
    Route::post('me', [ AuthController::class, 'me' ]);

    Route::post('show', [ OrderApiControllers::class, 'show' ]);
    Route::post('page', [ OrderApiControllers::class, 'page' ]);
    Route::post('create', [ OrderApiControllers::class, 'create' ])->name('api.create');
    Route::put('update', [ OrderApiControllers::class, 'update' ])->name('api.update');
    Route::delete('destroy', [ OrderApiControllers::class, 'destroy' ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
