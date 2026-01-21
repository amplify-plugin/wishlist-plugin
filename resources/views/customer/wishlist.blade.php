<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form id="customer-item-list-search-form" method="get" action="{{ url()->current() }}">
                <div class="row">
                    <div class="col-md-6 my-2 mb-md-0">
                        {{--                        <div class="d-flex justify-content-center justify-content-md-start">--}}
                        {{--                            <label aria-label="search">--}}
                        {{--                                <input type="search" aria-label="search" name="search"--}}
                        {{--                                       class="form-control form-control-sm"--}}
                        {{--                                       placeholder="Search...." value="{{ request('search') }}">--}}
                        {{--                            </label>--}}
                        {{--                        </div>--}}
                    </div>
                    <div class="col-md-6 mb-2 mb-md-0">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            <button type="button"
                                    class="btn btn-sm btn-success btn-right my-2 ml-2 mr-0"
                                    onclick="Amplify.addMultipleItemToCart(this, '#customer-item-list-search-form')">
                                {{__('Add all items to cart')}}
                            </button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive-md pb-4 pb-md-0">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered table-striped table-hover my-1"
                                           id="product-item-list">
                                        <thead>
                                        <tr>
                                            <th width="200">{{__('Product Code')}}</th>
                                            <th>{{__('Product')}}</th>
                                            <th>{{__('Notify')}}<i class="pe-7s-info font-weight-bolder ml-1"
                                                                   data-toggle="tooltip"
                                                                   title="System will sent a restock notification email."></i>
                                            </th>
                                            <th width="200">{{__('Quantity')}}</th>
                                            <th width="125px">{{__('Options')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($products as $key => $product)
                                            <tr class="added_products align-center" id="product-{{ $key }}">
                                                <td>
                                                    <input type="hidden"
                                                           name="products[{{ $key }}][product_warehouse_code]"
                                                           value="{{\ErpApi::getCustomerDetail()->DefaultWarehouse }}"/>
                                                    <input type="hidden" name="products[{{ $key }}][product_id]"
                                                           value="{{ $product->id }}"/>
                                                    <input type="text"
                                                           id="product-code-{{$key}}"
                                                           class="form-control form-control-sm"
                                                           readonly
                                                           name="products[{{$key}}][product_code]"
                                                           value="{{ $product->product_code }}"/>
                                                </td>
                                                <td class="align-baseline d-flex gap-2 justify-content-start">
                                                    <a class="text-decoration-none"
                                                       style="width: 90px; height: 90px"
                                                       href="{{ frontendSingleProductURL(optional($product)) }}">
                                                        <img title="View Product"
                                                             class="img-thumbnail product-thumb"
                                                             style="object-fit: contain; width: 100%; height: 100%"
                                                             src="{{ assets_image(optional($product)->productImage->main ?? '') }}"
                                                             alt="{{ optional($product)->product_name }}">
                                                    </a>
                                                    <div class="w-100">
                                                        <p class="d-block text-truncate text-primary font-weight-bold mb-2">
                                                            <a class="text-decoration-none"
                                                               href="{{ frontendSingleProductURL(optional($product)) }}">
                                                                {!! optional($product)->product_name ?? '' !!}
                                                            </a>
                                                        </p>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                Available Quantity:
                                                                <x-product.availability
                                                                        :product="$product"
                                                                        :value="$product?->total_quantity_available"/>
                                                            </div>
                                                            <div class="col-md-6 d-flex gap-2">
                                                                Price:
                                                                <x-product.price
                                                                        element="div"
                                                                        class="font-weight-bold d-flex"
                                                                        :product="$product"
                                                                        :value="$product->ERP?->Price"
                                                                        :uom="$product->ERP?->UnitOfMeasure ?? 'EA'"/>
                                                            </div>
                                                            @if(!empty($product->ship_restriction))
                                                                <div class="col-md-12">
                                                                    <p class="mt-2">{{ $product->ship_restriction }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <p class="text-danger d-block"
                                                           id="product-{{ $key }}-error"></p>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @if($product->notify)
                                                        <p class="text-success font-weight-bold">Yes</p>
                                                    @else
                                                        <p class="text-danger font-weight-bold">No</p>
                                                    @endif
                                                </td>
                                                <td width="200">
                                                    <x-cart.quantity-update
                                                            name="products[{{ $key }}][qty]"
                                                            :product="$product"
                                                            :index="$key"/>
                                                </td>
                                                <td>
                                                    <div class="btn-group m-0">
                                                        <button type="button"
                                                                class="btn btn-outline-warning mx-0 dropdown-toggle btn-sm"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item"
                                                               href="javascript:void(0)"
                                                               data-warehouse="{{ \ErpApi::getCustomerDetail()->DefaultWarehouse }}"
                                                               data-options="{{ json_encode(['code' => $product?->product_code ?? '']) }}"
                                                               onclick="Amplify.addSingleItemToCart(this, '{{ "#cart-item-{$key}" }}');">
                                                                <i class="icon-bag mr-1"></i>
                                                                {{ __('Add to Cart') }}
                                                            </a>
                                                            @if($product->notify)
                                                                <a class="dropdown-item"
                                                                   href="javascript:void(0);"
                                                                   onclick="Amplify.updateWishlistNotification({{ $product->id }}, false);">
                                                                    <i class="icon-ban mr-1"></i>
                                                                    {{ __('Don\'t Notify when Re-Stocked') }}
                                                                </a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                   href="javascript:void(0);"
                                                                   onclick="Amplify.updateWishlistNotification({{ $product->id }}, true);">
                                                                    <i class="icon-bell mr-1"></i>
                                                                    {{ __('Notify when Re-Stocked') }}
                                                                </a>
                                                            @endif

                                                            <a class="dropdown-item"
                                                               href="javascript:void(0);"
                                                               onclick="Amplify.removeWishListItem(this);"
                                                               data-url="{{ route('frontend.wishlist.destroy', $product->id) }}">
                                                                <i class="icon-trash mr-1"></i> {{ __('Remove') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    No data available in table
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-5">
                                    <label
                                            class="d-flex justify-content-center justify-content-md-start align-items-center"
                                            style="font-weight: 200;">
                                        Show
                                        <select name="per_page"
                                                onchange="$('#customer-item-list-search-form').submit();"
                                                class="form-control form-control-sm mx-1"
                                                style="width: 65px; background-position: 85%;">
                                            @foreach (getPaginationLengths() as $length)
                                                <option value="{{ $length }}"
                                                        @if ($length == request('par_page')) selected @endif>
                                                    {{ $length }}
                                                </option>
                                            @endforeach
                                        </select>
                                        entries
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    {!! $wishlist->withQueryString()->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            {{--            <form id="customer-item-list-search-form" method="get" action="{{ url()->current() }}">
                            <div class="row">
                                --}}{{--<div class="col-md-6 my-2 mb-md-0">
                                    <div class="d-flex justify-content-center justify-content-md-start">
                                        <div class="d-flex justify-content-center justify-content-md-start align-items-center">
                                            <label class="mb-0">
                                                <input type="text" name="search" class="form-control form-control-sm"
                                                       placeholder="Search...." value="{{ request('search') }}">
                                            </label>

                                            @if (request()->filled('search'))
                                                <a href="{{ route('frontend.wishlist.index') }}" class="btn btn-outline-link btn-sm ml-2 p-0" id="clear-search">
                                                    Clear
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>--}}{{--
                                <div class="col-12">
                                    <div class="row mx-0 justify-center-between list_shop_page_header d-none d-md-flex mb-3">
                                        <div class="col-md-1">
                                            <span class="list_view_header"></span>
                                        </div>
                                        <div class="col-md-11">
                                            <div class="row align-items-end">
                                                <div class="col-md-4 header_item_number">
                                                    <span class="list_view_header text-start">Item Number</span>
                                                </div>
                                                <div class="col-md-2 header_available_quantity d-flex justify-content-center">
                                                    <span class="list_view_header text-center">Available Qty.</span>
                                                </div>
                                                <div class="col-md-2 header_price">
                                                    <span class="list_view_header">Price</span>
                                                </div>
                                                <div class="col-md-2 header_quantity">
                                                    <span class="list_view_header text-center">Quantity</span>
                                                </div>
                                                <div class="col-2">
                                                    <span class="list_view_header"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div @class(["isotope-grid grid-no-gap mt-2 list cols-1"])>
                                        <div class="gutter-sizer"></div>
                                        <div class="grid-sizer"></div>
                                        @foreach ($products as $key => $product)
                                            <div class="grid-item p-1">
                                                <div class="product-row row mx-1">
                                                    <div class="col-12 px-0 col-md-1 text-center text-md-left product-image">
                                                        <a class="text-decoration-none"
                                                           href="{{ frontendSingleProductURL($product) }}">
                                                            <img src="{{ $product?->productImage?->main ?? '' }}" loading="lazy"
                                                                 alt="{{ $product->product_name ?? '' }}">
                                                        </a>
                                                    </div>

                                                    <div class="col-md-11 col-12 px-0">
                                                        <div class="row custom-product-details px-0 px-md-2">
                                                            <div class="px-0 col-12 col-md-4 text-center text-md-left product-code">
                                                                <a class="fw-700 font-weight-normal text-decoration-none text-black"
                                                                   href="{{ frontendSingleProductURL($product) }}">
                                                                    {{ $product->product_code ?? '' }}
                                                                </a>
                                                            </div>
                                                            <div
                                                                class="px-0 col-12 col-md-2 product-quantity text-black font-weight-normal">
                                                                @if($product->assembled)
                                                                    Assembled Item
                                                                @else
                                                                    <x-product.availability
                                                                        :product="$product"
                                                                        :value="$product->total_quantity_available"/>
                                                                @endif
                                                            </div>

                                                            <div class="px-0 col-12 col-md-2 my-2 my-md-0">
                                                                <x-product.price
                                                                    element="div"
                                                                    class="d-block fw-700 w-100 product-price font-weight-normal"
                                                                    :product="$product"
                                                                    :value="$product->ERP?->Price"
                                                                    :uom="$product->ERP?->UnitOfMeasure ?? 'EA'"
                                                                />
                                                            </div>
                                                            <div class="px-0 col-12 col-md-2 mb-2 mb-md-0 quantity_count_custom">
                                                                <div
                                                                    class="align-items-center d-flex product-count gap-2 justify-content-between w-100">
                                                                    <button
                                                                        type="button"
                                                                        style="width: 32px; height: 32px"
                                                                        class="text-black d-flex align-items-center justify-content-center fw-600 flex-shrink-0 border-card-qty"
                                                                        onclick="Amplify.handleQuantityChange('#product_qty_{{ $key }}', 'decrement');">
                                                                        <i class="icon-minus"></i>
                                                                    </button>
                                                                    <div
                                                                        class="border-card-qty align-items-center d-flex fw-600 justify-content-center"
                                                                        style="width: calc(100% - 56px - 16px)">
                                                                        <input type="hidden"
                                                                               disabled
                                                                               id="product_warehouse_{{ $product->product_code }}"
                                                                               value="{{ $product?->ERP?->WarehouseID ?? '' }}"/>
                                                                        <span class="d-none" id="customer_back_order_code"
                                                                              data-status="{{ optional(customer())->allow_backorder ? 'Y' : 'N' }}"></span>
                                                                        <input id="product_code_{{ $key }}"
                                                                               disabled
                                                                               name="product_code[]" type="hidden"
                                                                               value="{{ $product->product_code }}">
                                                                        <input id="product_id_{{ $key }}"
                                                                               disabled
                                                                               name="product_id[]" type="hidden"
                                                                               value="{{ $product->id ?? '' }}">
                                                                        <input id="{{ 'product_warehouse_' . $key }}"
                                                                               disabled
                                                                               type="hidden"
                                                                               value="{{ $product->ERP?->WarehouseID ?? \ErpApi::getCustomerDetail()->DefaultWarehouse }}"/>
                                                                        <input
                                                                            class="form-control-sm form-control fw-500 product-qty-{{ $product->product_code }} text-center"
                                                                            id="product_qty_{{ $key }}"
                                                                            data-product-code="{{ $product?->product_code ?? '' }}"
                                                                            data-min-order-qty="{{ $product?->min_order_qty ?? 1 }}"
                                                                            data-qty-interval="{{ $product?->qty_interval ?? 1 }}"
                                                                            placeholder="Min Qty: {{ $product?->min_order_qty ?? 1 }}"
                                                                            type="text"
                                                                            inputmode="number"
                                                                            oninput="Amplify.handleQuantityChange('#product_qty_{{ $key }}', 'input');"
                                                                            value="{{ $product?->min_order_qty ?? 1 }}"
                                                                            @if($key == 0) autofocus @endif
                                                                            min="{{ $product?->min_order_qty ?? 1 }}"
                                                                            step="{{ $product?->qty_interval  ?? 'any' }}"
                                                                            @if(!$product->allow_back_order) max="{{$product->total_quantity_available}}"
                                                                            @endif
                                                                            required/>
                                                                        <span class="d-none" id="product_back_order_{{ $key }}"
                                                                              data-status="{{ $product->allow_back_order ? 'Y' : 'N' }}"></span>
                                                                    </div>
                                                                    <button
                                                                        type="button"
                                                                        style="width: 32px; height: 32px"
                                                                        class="text-white bg-black d-flex align-items-center justify-content-center
                                                                          fw-600 flex-shrink-0 border-card-qty"
                                                                        onclick="Amplify.handleQuantityChange('#product_qty_{{ $key }}', 'increment');">
                                                                        <i class="icon-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-12 col-md-2 d-flex justify-content-center justify-content-md-end">
                                                                <button
                                                                    type="button"
                                                                    class="list_add_to_card_button"
                                                                    id="add_to_order_btn_{{ $key }}"
                                                                    data-toast-icon="icon-circle-check"
                                                                    onclick="addSingleProductToOrder('{{ $key }}')">
                                                                    {{ $addToCartBtnLabel() }}
                                                                </button>
                                                            </div>
                                                            <div class="px-0 col-12">
                                                                <a class="fw-700 d-block text-decoration-none text-black list_contant_product font-weight-normal"
                                                                   href="{{ frontendSingleProductURL($product) }}">
                                                                    {!! $product->product_name ?? '' !!}
                                                                </a>
                                                            </div>
                                                            <div class="px-0 col-12">
                                                                <div class="d-flex justify-content-between pb-1 font-weight-normal">
                                                                    <div
                                                                        class="text-black list_contant_product font-weight-normal mb-0">
                                                                        {!! $product->ship_restriction ?? null !!}
                                                                    </div>
                                                                    <div>
                                                                        <x-product.ncnr-item-flag :product="$product"
                                                                                                  :show-full-form="true"/>
                                                                    </div>

                                                                    <div>
                                                                        <x-product.default-document-link
                                                                            :document="$product->default_document"
                                                                            class="list_shop_datasheet_product pr-3"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-5">
                                    <label
                                        class="d-flex justify-content-center justify-content-md-start align-items-center"
                                        style="font-weight: 200;">
                                        Show
                                        <select name="per_page"
                                                onchange="$('#customer-item-list-search-form').submit();"
                                                class="form-control form-control-sm mx-1"
                                                style="width: 75px; background-position: 85%;">
                                            @foreach (getPaginationLengths() as $length)
                                                <option value="{{ $length }}"
                                                        @if ($length == request('per_page')) selected @endif>
                                                    {{ $length }}
                                                </option>
                                            @endforeach
                                        </select>
                                        entries
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    {!! $wishlist->withQueryString()->links() !!}
                                </div>
                            </div>
                        </form>--}}
        </div>
    </div>
</div>

@push('internal-script')
    <script>
        Amplify.removeWishListItem = function (target) {
            let actionLink = target.dataset.url;
            Amplify.confirm('Are you sure to remove this item?',
                'Wishlist', 'Remove', {
                    preConfirm: async function () {
                        return new Promise((resolve, reject) => {
                            $.ajax({
                                url: actionLink,
                                type: 'DELETE',
                                dataType: 'json',
                                header: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                success: function (result) {
                                    resolve(result);
                                },
                                error: function (xhr, status, err) {
                                    let response = JSON.parse(xhr.responseText);
                                    window.swal.showValidationMessage(response.message);
                                    window.swal.hideLoading();
                                    reject(false);
                                },
                            });
                        });
                    },
                    allowOutsideClick: () => !window.swal.isLoading()
                })
                .then(function (result) {
                    if (result.isConfirmed) {
                        Amplify.notify('success', result.value.message, 'Wishlist');
                        setTimeout(() => window.location.reload(), 2500)
                    }
                });
        };

        Amplify.updateWishlistNotification = function (productId, notify = true) {
            Amplify.confirm(notify
                    ? '{{ __('The system will send an email when the item becomes available.') }}'
                    : '{{ __('You will stop receiving notifications when this item becomes available.') }}',
                'Wishlist', 'Confirm', {
                    customClass: {
                        confirmButton: notify ? 'btn btn-primary' : 'btn btn-danger',
                    },
                    preConfirm: async function () {
                        return new Promise((resolve, reject) => {
                            $.ajax({
                                url: "{{ route('frontend.wishlist.notification') }}",
                                type: 'POST',
                                dataType: 'json',
                                data: {product_id: productId, notify: notify ? 1 : 0},
                                header: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                success: function (result) {
                                    resolve(result);
                                },
                                error: function (xhr, status, err) {
                                    let response = JSON.parse(xhr.responseText);
                                    window.swal.showValidationMessage(response.message);
                                    window.swal.hideLoading();
                                    reject(false);
                                },
                            });
                        });
                    },
                    allowOutsideClick: () => !window.swal.isLoading()
                })
                .then(function (result) {
                    if (result.isConfirmed) {
                        Amplify.notify('success', result.value.message, 'Wishlist');
                        setTimeout(() => window.location.reload(), 2500)
                    }
                });
        }
    </script>
@endpush


