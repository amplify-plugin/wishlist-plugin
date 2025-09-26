<?php

namespace Amplify\Wishlist;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class WishlistServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/wishlist.php', 'wishlist');

        $this->app->register(WidgetProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'wishlist');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->publishes([
            __DIR__ . '/../config/wishlist.php' => config_path('wishlist.php'),
        ], 'wishlist-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/wishlist'),
        ], 'wishlist-view');

        $this->app->booted(function () {
            $types = Config::get('amplify.cms.page_types', []);
            $types[] = [
                'code' => 'wishlist',
                'label' => 'Wishlist',
                'description' => 'Contact Wishlist Items',
                'middleware' => ['customers'],
                'reserved' => true,
                'url' => [
                    'type' => 'route',
                    'name' => 'frontend.wishlists.index',
                    'params' => '',
                ],
            ];
            
            Config::set('amplify.cms.page_types', $types);
        });
    }
}
