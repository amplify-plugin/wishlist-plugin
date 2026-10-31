<?php

use Amplify\Wishlist\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

//Backend Routes
Route::middleware(array_merge(config('backpack.base.web_middleware', ['web']),
    (array)config('backpack.base.middleware_key', 'admin'), ['admin_password_reset_required']))
    ->prefix(config('backpack.base.route_prefix', 'backpack'))
    ->namespace('Amplify\Wishlist\Http\Controllers\Backend')
    ->group(function () {
        Route::crud('wishlist', 'WishlistCrudController');
    });

//Frontend Routes
Route::name('frontend.wishlist.')->middleware(['web', 'customers', 'frontend'])->group(function () {
    Route::get('wishlist', [WishlistController::class, 'index'])->name('index');
    Route::post('wishlist', [WishlistController::class, 'add'])->name('store');

    Route::delete('wishlist/{product_id}', [WishlistController::class, 'remove'])
        ->name('destroy')
        ->whereNumber('product_id');

    Route::put('wishlist/{product_id}', [WishlistController::class, 'update'])
        ->name('update')
        ->whereNumber('product_id');

    Route::post('wishlist/notification', [WishlistController::class, 'notification'])
        ->name('notification');

    Route::get('wishlist/check/{product_id?}', [WishlistController::class, 'check'])
        ->name('check')
        ->whereNumber('product_id');

});
