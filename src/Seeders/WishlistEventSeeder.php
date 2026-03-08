<?php

namespace Amplify\Wishlist\Seeders;

use Amplify\System\Backend\Models\Event;
use Amplify\System\Traits\EventSeedTrait;
use Illuminate\Database\Seeder;

class WishlistEventSeeder extends Seeder
{
    use EventSeedTrait;

    private function data()
    {
        return [
            [
                'name' => 'Wishlist Product Restocked',
                'code' => Event::WISHLIST_PRODUCT_RESTOCKED,
                'enabled' => true,
                'description' => 'This event get triggered when program system received a wishlisted item is avaliable to order.',
                'eventVariables' => [
                    ['name' => '__full_name__', 'value' => '', 'description' => 'Contact full name who add the item to their wishlist'],
                    ['name' => '__product_name__', 'value' => '', 'description' => 'Wishlisted item name '],
                    ['name' => '__product_code__', 'value' => '', 'description' => 'Wishlisted item number/product code '],
                ],
                'eventRecipents' => [
                    ['name' => 'Admin', 'event_action_field' => 'is_get_admin', 'description' => 'System Administrator'],
                    ['name' => 'Customer', 'event_action_field' => 'is_get_customer', 'description' => 'Customer'],
                    ['name' => 'Business_Contact', 'event_action_field' => 'is_get_customer_business_contact', 'description' => 'Customer Business Contact'],
                    ['name' => 'Contact', 'event_action_field' => 'is_get_contact', 'description' => 'Contact'],
                ],
                'eventTemplates' => [
                    [
                        'name' => '[Customer] Wishlist Item Restocked Notification',
                        'subject' => 'Your Wishlist Item is Back in Stock',
                        'email_body' => <<<HTML
<p>Dear __full_name__,</p>

<p>Thank you for adding <strong>__product_name__</strong>&nbsp;to your wishlist and requesting to be notified when it is back in stock.</p>

<p>We&rsquo;re happy to let you know that you will receive an email as soon as this item becomes available again. We appreciate your interest and look forward to serving you.</p>

<p>If you no longer wish to receive restock notifications for this product, you can disable the notification option anytime from your account wishlist settings.</p>

<p>If you have any questions or need further assistance, please feel free to contact us.</p>

<p>&nbsp;</p>

<p>Best regards,<br />
__company_name__</p>
HTML,
                        'show_button' => true,
                        'button_text' => 'Wishlist Items',
                        'button_url' => route_uri('frontend.wishlist.index'),
                        'notification_type' => 'emailable',
                        'enabled' => true,
                        'created_at' => '2024-10-25 15:44:58',
                        'updated_at' => '2024-10-25 15:44:58',
                    ]
                ]
            ]
        ];
    }

}