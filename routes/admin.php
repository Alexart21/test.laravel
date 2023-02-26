<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DefaultController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\OrdersController;


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'can:admin']], function (){

    Route::get('/', [ DefaultController::class, 'index' ])->name('admin.index');

    Route::get('orders/add-product/{id}', [OrdersController::class, 'add'])->whereNumber(['id'])->name('orders.add');
    Route::post('orders/add-product', [OrdersController::class, 'save'])->name('orders.save');
    Route::post('orders/search', [OrdersController::class, 'search'])->name('orders.search');

    Route::delete('orders/delete/{id}', [OrdersController::class, 'delete'])->whereNumber(['id'])->name('orders.delete');

    Route::resource('/products', ProductsController::class)->parameters('id')->whereNumber(['id']);
    Route::resource('/orders', OrdersController::class)->parameters('id')->whereNumber(['id']);

    Route::get('api-test', [ DefaultController::class, 'api' ])->name('admin.api');
    Route::get('api-create', [ DefaultController::class, 'create' ])->name('admin.api.create');
    Route::get('api-update', [ DefaultController::class, 'update' ])->name('admin.api.udate');
});
