<?php

use Amplify\System\Backend\Http\Middlewares\ContactForceShippingAddressSelection;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

//Backend Routes
Route::middleware(array_merge(config('backpack.base.web_middleware', ['web']),
    (array)config('backpack.base.middleware_key', 'admin'), ['admin_password_reset_required']))
    ->prefix(config('backpack.base.route_prefix', 'backpack'))
    ->namespace( 'Amplify\Wishlist\Http\Controllers\Backend')
    ->group(function () {
        Route::crud('wishlist', 'WishlistCrudController');
    });

//Frontend Routes
Route::name('frontend.')->middleware(['web', ProtectAgainstSpam::class, ContactForceShippingAddressSelection::class])->group(function () {
    Route::resource('wishlists', \Amplify\Wishlist\Http\Controllers\Frontend\WishlistController::class)->where(['wishlist' => '[0-9]+']);
});
