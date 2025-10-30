<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form id="customer-item-list-search-form" method="get" action="{{ url()->current() }}">
                <div class="row">
                    <div class="col-md-6 my-2 mb-md-0">
                        <div class="d-flex justify-content-center justify-content-md-start">
                            <div class="d-flex justify-content-center justify-content-md-start align-items-center">
                                <label class="mb-0">
                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="Search...." value="{{ request('search') }}">
                                </label>

                                @if (request()->filled('search'))
                                    <a href="{{ route('frontend.wishlist.index') }}"
                                        class="btn btn-outline-link btn-sm ml-2 p-0" id="clear-search">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive-md pb-4 pb-md-0">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered table-striped table-hover my-1">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Product') }}</th>
                                                <th>{{ __('Quantity Available') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($products as $key => $product)
                                                <tr>
                                                    <td>
                                                        <div
                                                            class="d-flex justify-content-start align-content-center gap-2">
                                                            <img class="rounded-top aspect-square object-contain"
                                                                style="width: 15%"
                                                                src="{{ $product->productImage->main ?? '' }}"
                                                                alt="Product">
                                                            <div>
                                                                <div class="d-flex gap-2">
                                                                    {{ $product->product_name }}
                                                                </div>
                                                                <div>Product Code: {{ $product->product_code }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div
                                                            class="align-items-center d-flex product-count gap-2 justify-content-start">
                                                            <span
                                                                class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 border-card-qty"
                                                                onclick="productQuantity({{ $product->id }}, 'minus', {{ $product->qty_interval ?? 1 }}, {{ $product->min_order_qty ?? 1 }})">
                                                                <i class="icon-minus"></i>
                                                            </span>

                                                            <input type="text" id="product_qty_{{ $product->id }}"
                                                                class="item-quantity cart-item-{{ $product->id }} font-weight-bold mx-2 p-2 text-center border rounded"
                                                                value="{{ number_format($product->total_quantity_available) }}"
                                                                name="cart-item-qty[{{ $product->id }}]">

                                                            <span
                                                                class="text-white bg-black d-flex align-items-center justify-content-center fw-600 flex-shrink-0 border-card-qty"
                                                                onclick="productQuantity({{ $product->id }}, 'plus', {{ $product->qty_interval ?? 1 }}, {{ $product->min_order_qty ?? 1 }})">
                                                                <i class="icon-plus"></i>
                                                            </span>
                                                        </div>
                                                        <button class="add_to_cart_custom"
                                                            id="add_to_order_btn_{{ $key }}"
                                                            data-toast-icon="icon-circle-check"
                                                            onclick="addSingleProductToOrder('{{ $key }}')">
                                                            {{ $addToCartBtnLabel() }}
                                                        </button>
                                                    </td>
                                                    <td>
                                                        {{ currency_format($product->ERP->Price, null, true) }} /
                                                        {{ $product->ERP->UnitOfMeasure }}
                                                    </td>

                                                    {{-- @php
                                                    dd($product);
                                                @endphp --}}
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">
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
                        </div>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
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

        (function() {
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
            document.addEventListener('click', function(e) {
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
