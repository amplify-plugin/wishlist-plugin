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
                                    <a href="{{ route('frontend.wishlist.index') }}" class="btn btn-outline-link btn-sm ml-2 p-0" id="clear-search">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{--                    @if (customer(true)->can('ship-to-addresses.add'))--}}
                    {{--                        <div class="col-md-6 mb-2 mb-md-0">--}}
                    {{--                            <div class="d-flex justify-content-center justify-content-md-end">--}}
                    {{--                                <a class="btn btn-sm btn-primary mr-0" href="{{ route('frontend.addresses.create') }}">--}}
                    {{--                                    <i class="icon-plus"></i> Add Address--}}
                    {{--                                </a>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    @endif--}}
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
                                        @forelse($products as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        {{ $product->product_name }}
                                                    </div>
                                                </td>
                                                <td>
                                                        {{ number_format($product->total_quantity_available) }}
                                                </td>
                                                <td>
                                                    {{ currency_format($product->ERP->Price, null, true) }}
                                                </td>

{{--                                                @if ($columns['address_code'])--}}
{{--                                                    <td>{{ $address->address_code }}</td>--}}
{{--                                                @endif--}}

{{--                                                @if ($columns['address_name'])--}}
{{--                                                    <td>{{ $address->address_name }}</td>--}}
{{--                                                @endif--}}

{{--                                                @if ($columns['address_line'])--}}
{{--                                                    <td>--}}
{{--                                                        {!! $address->address_1 ? "{$address->address_1}</br>": null !!}--}}
{{--                                                        {!! $address->address_2 ? "{$address->address_2}</br>": null !!}--}}
{{--                                                        {!! $address->address_3 ? "{$address->address_3}</br>": null !!}--}}
{{--                                                    </td>--}}
{{--                                                @endif--}}

{{--                                                @if ($columns['city'])--}}
{{--                                                    <td>--}}
{{--                                                        {!! $address->city ?? null  !!}--}}
{{--                                                    </td>--}}
{{--                                                @endif--}}

{{--                                                @if ($columns['state'])--}}
{{--                                                    <td>--}}
{{--                                                        {!! $address->stateModel?->name ?? null  !!}--}}
{{--                                                    </td>--}}
{{--                                                @endif--}}


{{--                                                @if ($columns['zip_code'])--}}
{{--                                                    <td>{{ $address->zip_code }}</td>--}}
{{--                                                @endif--}}

{{--                                                @if ($columns['country'])--}}
{{--                                                    <td>--}}
{{--                                                        {!! $address->country?->name ? "{$address->country?->name}" : "{$address->country_code}"  !!}--}}
{{--                                                    </td>--}}
{{--                                                @endif--}}
{{--                                                @if (checkPermissionLength(['ship-to-addresses.view', 'ship-to-addresses.update', 'ship-to-addresses.remove']) > 0)--}}
{{--                                                    <td class="text-right" style="width: 125px">--}}
{{--                                                        <div class="btn-group m-0">--}}
{{--                                                            <button type="button"--}}
{{--                                                                    class="btn btn-outline-warning mx-0 dropdown-toggle btn-sm"--}}
{{--                                                                    data-toggle="dropdown" aria-expanded="false">--}}
{{--                                                                Actions--}}
{{--                                                            </button>--}}
{{--                                                            <div class="dropdown-menu dropdown-menu-right">--}}
{{--                                                                @if (!$address->isDefaultAddress())--}}
{{--                                                                    <a class="dropdown-item"--}}
{{--                                                                       href="{{ route('frontend.addresses.default-address', $address->id) }}">--}}
{{--                                                                        <i class="icon-circle-check mr-1"></i> Set--}}
{{--                                                                        As--}}
{{--                                                                        Default--}}
{{--                                                                    </a>--}}
{{--                                                                @endif--}}
{{--                                                                @if (customer(true)->can('ship-to-addresses.view'))--}}
{{--                                                                    <a class="dropdown-item"--}}
{{--                                                                       href="{{ route('frontend.addresses.show', $address->id) }}">--}}
{{--                                                                        <i class="icon-eye mr-1"></i> Preview--}}
{{--                                                                    </a>--}}
{{--                                                                @endif--}}
{{--                                                                @if (customer(true)->can('ship-to-addresses.update'))--}}
{{--                                                                    <a class="dropdown-item"--}}
{{--                                                                       href="{{ route('frontend.addresses.edit', $address->id) }}">--}}
{{--                                                                        <i class="icon-paper-clip mr-1"></i> Edit--}}
{{--                                                                    </a>--}}
{{--                                                                @endif--}}
{{--                                                                @if (customer(true)->can('ship-to-addresses.remove'))--}}
{{--                                                                    <a class="dropdown-item delete-modal"--}}
{{--                                                                       href="{{ route('frontend.addresses.destroy', $address->id) }}"--}}
{{--                                                                       data-target="#delete-modal"--}}
{{--                                                                       data-toggle="modal"--}}
{{--                                                                       onclick="setFormAction(this)">--}}
{{--                                                                        <i class="icon-trash mr-1"></i> Delete--}}
{{--                                                                    </a>--}}
{{--                                                                @endif--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </td>--}}
{{--                                                @else--}}
{{--                                                    @if (!$address->isDefaultAddress())--}}
{{--                                                        @include(--}}
{{--                                                            'widget::customer.permission-component',--}}
{{--                                                            [--}}
{{--                                                                'data' => $address,--}}
{{--                                                                'label' => 'Set As Default',--}}
{{--                                                                'route' => route(--}}
{{--                                                                    'frontend.addresses.default-address',--}}
{{--                                                                    $address->id),--}}
{{--                                                            ]--}}
{{--                                                        )--}}
{{--                                                    @endif--}}
{{--                                                    @if (customer(true)->can('ship-to-addresses.view'))--}}
{{--                                                        @include(--}}
{{--                                                            'widget::customer.permission-component',--}}
{{--                                                            [--}}
{{--                                                                'data' => $address,--}}
{{--                                                                'label' => 'Preview',--}}
{{--                                                                'route' => route(--}}
{{--                                                                    'frontend.addresses.show',--}}
{{--                                                                    $address->id),--}}
{{--                                                            ]--}}
{{--                                                        )--}}
{{--                                                    @endif--}}
{{--                                                    @if (customer(true)->can('ship-to-addresses.update'))--}}
{{--                                                        @include(--}}
{{--                                                            'widget::customer.permission-component',--}}
{{--                                                            [--}}
{{--                                                                'data' => $address,--}}
{{--                                                                'label' => 'Edit',--}}
{{--                                                                'route' => route(--}}
{{--                                                                    'frontend.addresses.edit',--}}
{{--                                                                    $address->id),--}}
{{--                                                            ]--}}
{{--                                                        )--}}
{{--                                                    @endif--}}
{{--                                                    @if (customer(true)->can('ship-to-addresses.remove'))--}}
{{--                                                        <a href="{{ route('frontend.addresses.destroy', $address->id) }}"--}}
{{--                                                           class="delete-modal badge btn-info text-decoration-none mb-1"--}}
{{--                                                           onclick="setFormAction(this)" data-toggle="modal"--}}
{{--                                                           data-target="#delete-modal">{{ $label }}</a>--}}
{{--                                                    @endif--}}
{{--                                                @endif--}}
                                            </tr>
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
                                                style="width: 65px; background-position: 85%;">
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
