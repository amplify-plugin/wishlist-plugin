<div {!! $htmlAttributes !!}>
    <button
        class="btn btn-outline-{{$variant}}"
        type="button"
        data-toggle="tooltip"
        aria-label="Add to Wishlist"
        title="@if($alreadyExists) Remove from Wishlist @else Add to Wishlist @endif"
        @if(customer_check())
            @if($hasPermission())
                onclick="addToWishlist();"
            @else
                data-toast
                data-toast-type="warning"
                data-toast-position="topRight"
                data-toast-icon="icon-circle-cross"
                data-toast-title="Wishlist"
                data-toast-message="You don't have permission to use this feature"
            @endif
        @else
            data-toast
            data-toast-type="warning"
            data-toast-position="topRight"
            data-toast-icon="icon-circle-cross"
            data-toast-title="Wishlist"
            data-toast-message="You need to be logged in to access this feature."
        @endif
    >
        {{ $addLabel ?? '' }}
    </button>
</div>

@pushonce('footer-script', 'wishlist-button')
    <script>
        function removeFromWishlist() {

        }

        function addToWishlist() {

        }

        function checkOnWishlist() {

        }
    </script>
@endpushonce
