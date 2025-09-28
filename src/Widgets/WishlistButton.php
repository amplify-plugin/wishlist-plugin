<?php

namespace Amplify\Wishlist\Widgets;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;

class WishlistButton extends BaseComponent
{
    public function __construct(public Product|ItemRow $product)
    {
        parent::__construct();
    }

    public function render()
    {
        $product_id = $this->product instanceof ItemRow ? $this->product->Amplify_Id : $this->product->id;

        return view('wishlist::wishlist-button', compact( 'product_id'));
    }

    public function hasPermission()
    {
        return true;
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['btn']);

        return parent::htmlAttributes();
    }
}
