<button
        {!! $htmlAttributes !!}
        id="product-{{$product_id}}"
        type="button"
        data-state="false"
        data-toggle="tooltip"
        aria-label="Add to Wishlist"
        onclick="syncWishlist({{$product_id}});"
>
    {{ $addLabel ?? '' }}
</button>
<div style="display: none">
    <div id="add-product-{{$product_id}}">
        {{ $addLabel ?? '' }}
    </div>
    <div id="remove-product-{{$product_id}}">
        {{ $removeLabel ?? '' }}
    </div>
</div>

@pushonce('footer-script')
    <script>
        function syncWishlist(product_id) {
            if(!Amplify.authenticated()) {
                Amplify.alert('You need to be logged in to access this feature.', 'Wishlist');
                return;
            }

            ($("#product-" + product_id).data('state') === 'true')
                ? removeFromWishlist(product_id)
                : addToWishlist(product_id);
        }

        function removeFromWishlist(product_id) {
            $.ajax({
                url: '{{ route('frontend.wishlist.index') }}/' + product_id,
                method: 'DELETE',
                dataType: "JSON",
                success: function () {
                    ShowNotification('success', 'Wishlist', 'Product removed from wishlist.');
                },
                error: function (xhr) {
                    ShowNotification('error', 'Wishlist', JSON.parse(xhr.responseText).message ?? 'Something went wrong. Please try again later.');
                }
            }).done(function () {
                checkOnWishlist('product-' + product_id, product_id);
            });
        }

        function addToWishlist(product_id) {

            $.post('{{ route('frontend.wishlist.store') }}', {product_id}, function () {
                ShowNotification('success', 'Wishlist', 'Product added to wishlist.');
            }, 'JSON')
                .fail(function (xhr) {
                    ShowNotification('error', 'Wishlist', JSON.parse(xhr.responseText).message ?? 'Something went wrong. Please try again later.');
                }).done(function () {
                checkOnWishlist(product_id);
            });
        }

        function checkOnWishlist(product_id) {

            var button = $("#product-" + product_id);
            var addTemplate = $(`#add-product-${product_id}`).html();
            var removeTemplate = $(`#remove-product-${product_id}`).html();
            var variant = button.data('add-variant');

            $.get('{{ route('frontend.wishlist.check') }}/' + product_id, function (response) {
                button.empty();
                if (response.exists) {
                    button.data('state', response.data.id ? 'true' : 'false');
                    button.attr("class").includes('outline') ? button.addClass('btn-outline-danger') : button.addClass('btn-danger');
                    button.append(removeTemplate);
                } else {
                    button.addClass('btn-' + variant);
                    button.append(addTemplate);
                }
            }, 'JSON');
        }
    </script>
@endpushonce

@if(customer_check())
    @pushonce('footer-script')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                checkOnWishlist({{$product_id}});
            });
        </script>
    @endpushonce
@endif
