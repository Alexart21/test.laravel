<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DefaultController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\OrdersController;


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'can:admin']], function (){
    Route::get('/', [ DefaultController::class, 'index' ])->name('admin.index');
});
