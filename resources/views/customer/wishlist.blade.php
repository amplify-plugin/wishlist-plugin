<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            @foreach($wishlist as $item)
                <div class="product-sku-item text-black">
                    <div
                        class="w-100 d-flex justify-content-between align-items-sm-center gap-4 flex-column flex-sm-row">
                        <div class="product-img">
                            <img src="" alt="product-img">
                        </div>
                        <div>
                            <div class="text-uppercase fs-16">{{ $item->product->product_code }}</div>
                            <div class="mb-2 font-roboto">{{ $item->product->product_name }}</div>
                            <div class="specs-container">
                                <div class="spec-column">
                                    <div class="text-nowrap">Voltage (AC) :</div>
                                    <div class="font-roboto text-nowrap">250 VAC</div>

                                    <div class="text-nowrap">Amperage :</div>
                                    <div class="font-roboto text-nowrap">3 A</div>

                                    <div class="text-nowrap">Body Type :</div>
                                    <div class="font-roboto text-nowrap">Glass</div>
                                </div>

                                <div class="spec-column">
                                    <div class="text-nowrap">Fuse Type :</div>
                                    <div class="font-roboto text-nowrap">Time Delay</div>

                                    <div class="text-nowrap">Size :</div>
                                    <div class="font-roboto text-nowrap">5 x 20mm</div>
                                </div>

                                <div class="spec-column">
                                    <div class="text-nowrap">Last Notified At :</div>
                                    <div class="font-roboto text-nowrap">2-3 weeks</div>

                                    <div class="text-nowrap">Available Qyt :</div>
                                    <div class="font-roboto text-nowrap">80000</div>

                                    <div class="text-nowrap">Price :</div>
                                    <div class="font-roboto text-nowrap">$1.88/each</div>
                                </div>

                            </div>
                        </div>
                        <div class="d-flex gap-3 align-self-sm-end flex-wrap">
                            <div class="d-flex gap-2 flex-column m-0">
                                <button class="flex-center gap-2 btn btn-block btn-outline-primary btn-sm m-0">
                                    <i class="icon-heart"></i>
                                    Add to Wishlist
                                </button>
                                <x-product-shopping-list :product-id="1"/>
                            </div>
                            <div class="d-flex flex-column align-self-end gap-2">
                                <div class="w-100">
                                    <div class="fw-500 align-self-center">Quantity:</div>
                                    <div
                                        class="align-items-center d-flex product-count mt-2 gap-2 justify-content-between">
                                        {{--<span
                                            class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"
                                            onclick="productQuantity(1,'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                                             <i class="icon-minus fw-700"></i>
                                         </span>

                                        <input type="text" class="form-control form-control-sm text-center"
                                               style="height: 30px; border-radius: 0 !important; border: 1px solid #999999;"
                                               id="product_qty_1"
                                               name="qty[]" value="{{ $product?->min_order_qty ?? 1 }}"
                                               min="{{ $product?->min_order_qty ?? 1 }}" step="{{ $product?->qty_interval }}"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                                        <input type="hidden" id="product_code_1" value="{{ $product->Product_Code }}" />
                                        <input id="product_warehouse_1" type="hidden"
                                               value="{{ optional(optional(customer(true))->warehouse)->code }}" />
                                        <input type="hidden" id="product_warehouse_{{ $product->Product_Code }}"
                                               value="{{ $product?->ERP?->WarehouseID ?? '' }}" />

                                        <span class="text-white bg-dark d-flex align-items-center justify-content-center
                                             fw-600 flex-shrink-0 rounded border"
                                              onclick="productQuantity(1,'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                                             <i class="icon-plus fw-700"></i>
                                         </span>--}}
                                    </div>
                                </div>
                                @if(customer(true)->can('order.add-to-cart'))
                                <button class="add_to_cart_custom">Add to cart</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between gap-4 w-100 flex-wrap">
                        <div>
                            {!!  $item->product->ship_restriction ?? null !!}
                        </div>
                        <x-product.ncnr-item-flag :product="$item->product" :show-full-form="true"/>
                        <div class="d-flex gap-2">
                            <x-product.default-document-link
                                :document="$item->product->default_document"
                                class="list_shop_datasheet_product"/>
                        </div>
                    </div>
                </div>
            @endforeach
            {!! $wishlist->links() !!}
        </div>
    </div>
</div>

<script>
    function productQuantity(id, type, interval, min) {
        let item = document.getElementById(`product_qty_${id}`);
        let val = parseInt(item.value);
        switch (type) {
            case 'plus':
                item.value = val + interval;
                break;
            case 'minus':
                if (val > min) {
                    item.value = val - interval;
                }
                break;
        }
    }
</script>
