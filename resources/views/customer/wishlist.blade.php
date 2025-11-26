<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form id="customer-item-list-search-form" method="get" action="{{ url()->current() }}">
                <div class="row">
                    {{--<div class="col-md-6 my-2 mb-md-0">
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
                    </div>--}}
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
                                                                   value="{{ $product->ERP->WarehouseID }}"/>
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
            </form>
        </div>
    </div>
</div>

@push('html-default')
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true" data-backdrop="static"
         data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger d-flex align-items-center p-3">
                    <h5 class="modal-title text-white">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST" class="d-inline" id="form-delete">
                    @method('delete')
                    @csrf
                    <div class="modal-body">
                        <p class="text-center">{{ __('Are you sure you want to delete this item?') }}</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger" name="delete_user">{{ __('Delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('internal-script')
    <script>
        function setFormAction(e) {
            const form = $('#form-delete');
            const deleteBtn = $(e);
            form.attr('action', deleteBtn.attr('href'));
        }

        (function () {
            // Build a URL that keeps all params except `search` and `page`
            function buildUrlPreservingOthers(formAction) {
                var current = new URL(window.location.href);

                // If a form action is set, use its pathname but keep current search params
                if (formAction) {
                    var base = new URL(formAction, window.location.origin);
                    current.pathname = base.pathname; // ensures no duplicated path segments
                }

                current.searchParams.delete('search');
                current.searchParams.delete('page');
                return current.pathname + (current.search || '');
            }

            // Use event delegation so it works no matter when the button is rendered
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('#clear-search');
                if (!btn) return;

                e.preventDefault();
                var form = document.getElementById('customer-item-list-search-form');
                var action = form ? form.getAttribute('action') : null;

                var next = buildUrlPreservingOthers(action);
                window.location.assign(next);
            }, true);
        })();
    </script>
@endpush



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
