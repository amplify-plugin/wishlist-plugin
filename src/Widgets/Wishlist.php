<?php

namespace Amplify\Wishlist\Widgets;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class Wishlist extends BaseComponent
{
    public function render()
    {
        $contact = customer(true);

        /**
         * @var $wishlist LengthAwarePaginator
         */
        $wishlist = \Amplify\Wishlist\Models\Wishlist::where('customer_id', $contact->customer_id)
            ->where('contact_id', $contact->id)
            ->paginate(request('per_page', getPaginationLengths()[0]));

        $products = Product::whereIn('id', array_unique($wishlist->pluck('product_id')->toArray()))->get();

        $codes = [];

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

        $warehouse_codes = array_unique([$erpCustomer->DefaultWarehouse, customer()?->warehouse?->code, config('amplify.frontend.guest_checkout_warehouse')]);

        $products = $products->map(function ($product) use ($priceAndAvail, $warehouse_codes) {
            $filteredPriceAvailability = $priceAndAvail
                ->where('ItemNumber', $product->product_code)
                ->whereIn('WarehouseID', $warehouse_codes);

            $product->ERP = $filteredPriceAvailability->isNotEmpty()
                ? $filteredPriceAvailability->first()
                : $priceAndAvail->where('ItemNumber', $product->product_code)
                    ->first();

            $product->total_quantity_available = $priceAndAvail->where('ItemNumber', $product->productCode)->sum('QuantityAvailable');

            return $product;
        });

        return view('wishlist::customer.wishlist', compact('products', 'wishlist'));
    }

    public function addToCartBtnLabel (){
        return 'add to cart';
    }
}
