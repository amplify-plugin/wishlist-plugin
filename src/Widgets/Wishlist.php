<?php

namespace Amplify\Wishlist\Widgets;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;
use Illuminate\Support\Str;

class Wishlist extends BaseComponent
{
    public function render()
    {
        $contact = customer(true);

        $wishlist = \Amplify\Wishlist\Models\Wishlist::where('customer_id', $contact->customer_id)
            ->where('contact_id', $contact->id)
            ->paginate(5);

        $products = Product::with('attributes')->whereIn('id', array_unique($wishlist->pluck('product_id')->toArray()))->get();

        $codes = [];
        $products->each(fn($item) => $codes[] = ['item' => $item->product_code, 'uom' => $item->uom]);

        foreach ($products as $product) {
            $codes[] = [
                'item' => $product->product_code,
                'uom' => $product->uom
            ];
        }

        $warehouses = ErpApi::getWarehouses([['enabled', '=', true]]);

        $warehouseString = $warehouses->pluck('WarehouseNumber')->implode(',');

        $erpCustomer = ErpApi::getCustomerDetail();
        if (!Str::contains($warehouseString, $erpCustomer->DefaultWarehouse)) {
            $warehouseString = "$warehouseString,{$erpCustomer->DefaultWarehouse}";
        }
        $priceAndAvail = ErpApi::getProductPriceAvailability([
            'items' => $codes,
            'warehouse' => $warehouseString
        ]);

        $wishlist->map(function ($item) use ($priceAndAvail) {
            $product = $item->product;
            $product->remove_from_cart = $item->remove_from_cart;
            $product->last_notified_at = $item->last_notified_at;
            $product->total_quantity_available = $priceAndAvail->where('ItemNumber', $product->productCode)->sum('QuantityAvailable');
            return $product;
        });

//        dd($wishlist);

        return view('wishlist::customer.wishlist', compact('wishlist'));
    }
}
