<?php

use Amplify\System\Backend\Http\Middlewares\ContactForceShippingAddressSelection;
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
Route::name('frontend.wishlist.')->middleware(['web', ProtectAgainstSpam::class, ContactForceShippingAddressSelection::class, 'auth:customer'])->group(function () {
    Route::get('wishlist', [WishlistController::class, 'index'])->name('index');
    Route::post('wishlist', [WishlistController::class, 'add'])->name('store');

    Route::delete('wishlist/{product_id}', [WishlistController::class, 'remove'])
        ->name('destroy')
        ->where(['product_id' => '[0-9]+']);

    Route::put('wishlist/{product_id}', [WishlistController::class, 'update'])
        ->name('update')
        ->where(['product_id' => '[0-9]+']);

    Route::get('wishlist/check/{product_id?}', [WishlistController::class, 'check'])->name('check');

});
