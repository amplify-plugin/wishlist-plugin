<?php

namespace Amplify\Wishlist\Seeders;

use Amplify\System\Backend\Models\Event;
use Amplify\System\Traits\EventSeedTrait;
use Illuminate\Database\Seeder;

class WishlistNewFieldSeeder extends Seeder
{
    public function run()
    {
        if($event = Event::whereCode(Event::WISHLIST_PRODUCT_RESTOCKED)->first()) {
            $event->eventVariables()->create([
                'name' => '__product_detail_link__', 'value' => '', 'description' => 'Product Detail Page Link'
            ]);
        }
    }

}