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

        $products = Product::select(DB::raw('*, product_code AS Product_Code'))->whereIn('id', $ids)->with('productImage')->get();

        $productDefaultDocumentTypes = DocumentType::select(['document_types.*', 'document_type_product.file_path', 'document_type_product.product_id'])
            ->join('document_type_product', function (JoinClause $join) use ($ids) {
                return $join->whereIn('product_id', $ids);
            })
            ->where('document_types.id', '=', config('amplify.pim.document_type'))
            ->get();

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

        $products = $products->map(function ($product) use ($priceAndAvail, $warehouse_codes, $productDefaultDocumentTypes) {
            $filteredPriceAvailability = $priceAndAvail
                ->where('ItemNumber', $product->Product_Code)
                ->whereIn('WarehouseID', $warehouse_codes);

            $product->ERP = $filteredPriceAvailability->isNotEmpty()
                ? $filteredPriceAvailability->first()
                : $priceAndAvail->where('ItemNumber', $product->Product_Code)
                    ->first();

            $product->avaliable = $priceAndAvail
                ->where('ItemNumber', $product->Product_Code)
                ->where('QuantityAvailable', '>=', 1)
                ->count();

            $product->Product_Name = strip_tags(explode('<B>', $product->Product_Name)[0] ?? '');
            $product->total_quantity_available = $priceAndAvail->where('ItemNumber', $product->Product_Code)->sum('QuantityAvailable');
            $orderList = $this->productExistOnFavorite($product->id, $product);
            $product->exists_in_favorite = $orderList != null;
            $product->favorite_list_id = $orderList->id ?? null;
            $ownProduct = $product;
            $product->mpn = $ownProduct->manufacturer ?? 'N/A';
            $product->min_order_qty = $product->ERP->MinOrderQuantity ?? $ownProduct->min_order_qty;
            $product->qty_interval = $product->ERP->QuantityInterval ?? $ownProduct->qty_interval;
            $product->allow_back_order = $product->ERP->AllowBackOrder ?? $ownProduct->allow_back_order ?? false;
            $product->default_document = $productDefaultDocumentTypes->firstWhere('product_id', '=', $product->id);
            $product->in_stock = $ownProduct?->vendornum == 3160 ? true : $ownProduct->in_stock ?? false;
            $product->is_ncnr = $ownProduct?->is_ncnr ?? false;
            $product->ship_restriction = $ownProduct->ship_restriction ?? false;
            $product->availability = $ownProduct->availability ?? ProductAvailabilityEnum::Actual;
            $product->assembled = $ownProduct?->vendornum == 3160;
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