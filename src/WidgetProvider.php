<?php

namespace Amplify\Wishlist;

use Amplify\Widget\Abstracts\Widget;
use Amplify\Wishlist\Widgets\Wishlist;
use Amplify\Wishlist\Widgets\WishlistButton;
use Illuminate\Support\ServiceProvider;

class WidgetProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $widgets = [
            Wishlist::class => [
                'name' => 'customer.wishlist',
                'reserved' => true,
                'internal' => false,
                '@inside' => null,
                '@client' => null,
                'model' => ['wishlist'],
                '@attributes' => [],
                '@nestedItems' => [],
                'description' => 'Allow customers to manage their wish list items',
            ],
            WishlistButton::class => [
                'name' => 'wishlist-button',
                'reserved' => true,
                'internal' => true,
                'model' => [],
                '@inside' => null,
                '@client' => null,
                '@attributes' => [],
                '@nestedItems' => [
                    ['name' => 'x-slot::add-label'],
                    ['name' => 'x-slot::remove-label'],
                ],
                'description' => 'Allow customer to add or remove item to wishlist',
            ],
        ];

        foreach ($widgets as $namespace => $options) {
            Widget::register($namespace, $options['name'], $options);
        }
    }
}
