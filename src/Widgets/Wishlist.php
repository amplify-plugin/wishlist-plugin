<?php

namespace Amplify\Wishlist\Widgets;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Enums\ProductAvailabilityEnum;
use Amplify\System\Backend\Models\DocumentType;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Wishlist extends BaseComponent
{
    public $orderList;

    public function render()
    {
        $contact = customer(true);

        /**
         * @var $wishlist LengthAwarePaginator
         */
        $wishlist = \Amplify\Wishlist\Models\Wishlist::where('customer_id', $contact->customer_id)
            ->where('contact_id', $contact->id)
            ->paginate(request('per_page', getPaginationLengths()[0]));

        $ids = array_unique($wishlist->pluck('product_id')->toArray());

        $products = Product::whereIn('id', $ids)->with('productImage')->get();

        $codes = [];

        foreach ($products as $product) {
            $codes[] = [
                'item' => $product->product_code,
                'uom' => $product->uom,
                'qty' => $product->min_order_qty,
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

        $products = $products->map(function ($product) use ($priceAndAvail, $warehouse_codes, $wishlist) {
            $filteredPriceAvailability = $priceAndAvail
                ->where('ItemNumber', $product->product_code)
                ->whereIn('WarehouseID', $warehouse_codes);

            $product->ERP = $filteredPriceAvailability->isNotEmpty()
                ? $filteredPriceAvailability->first()
                : $priceAndAvail->where('ItemNumber', $product->product_code)
                    ->first();

            $product->avaliable = $priceAndAvail
                ->where('ItemNumber', $product->product_code)
                ->where('QuantityAvailable', '>=', 1)
                ->count();

            $product->product_name = strip_tags(explode('<B>', $product->product_name)[0] ?? '');
            $product->total_quantity_available = $priceAndAvail->where('ItemNumber', $product->product_code)->sum('QuantityAvailable');
            $product->mpn = $product->manufacturer ?? 'N/A';
            $product->min_order_qty = $product->ERP->MinOrderQuantity ?? $product->min_order_qty;
            $product->qty_interval = $product->ERP->QuantityInterval ?? $product->qty_interval;
            $product->allow_back_order = $product->ERP->AllowBackOrder ?? $product->allow_back_order ?? false;
            $product->availability = $product->availability ?? ProductAvailabilityEnum::Actual;
            $product->notify = $wishlist->firstWhere('product_id', $product->id)->notify ?? false;
            $product->pricing = true;
            return $product;
        });

        return view('wishlist::customer.wishlist', compact('products', 'wishlist'));
    }

    public function addToCartBtnLabel(): string
    {
        return $this->cartButtonLabel ?? 'Add To Cart';
    }

    protected function productExistOnFavorite($id, &$product): ?OrderListItem
    {
        if ($this->orderList && $this->showFavourite) {
            foreach ($this->orderList as $orderList) {
                if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                    return $item;
                }
            }
        }

        return null;
    }
}