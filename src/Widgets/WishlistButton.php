<?php

namespace Amplify\Wishlist\Widgets;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;

class WishlistButton extends BaseComponent
{
    public function __construct(public Product|ItemRow $product, public string $variant = 'primary')
    {
        parent::__construct();
    }

    public function render()
    {
        $contact = customer(true);

        $contact_id = $contact->id;

        $customer_id = $contact->customer_id;

//        $product_id = $this->product instanceof ItemRow ? $this->product->Amplify_Id : $this->product->id;
        $product_id = null;

        return view('wishlist::wishlist-button', compact('contact_id', 'customer_id', 'product_id'));
    }

    public function hasPermission()
    {
        return true;
    }
}
