<?php

namespace Amplify\Wishlist\Http\Controllers\Frontend;

use Amplify\Frontend\Http\Requests\FavoriteListRequest;
use Amplify\Frontend\Http\Requests\UpdateOrderListRequest;
use Amplify\Frontend\Traits\HasDynamicPage;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\Wishlist\Http\Requests\WishlistNotificationRequest;
use Amplify\Wishlist\Models\Wishlist;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
    use HasDynamicPage;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!in_array(true, [customer(true)->can('wishlist.list')])) {
            abort(403);
        }

        $this->loadPageByType('wishlist');

        return $this->render();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function add(Request $request): JsonResponse
    {
        try {

            if (empty($request->input('product_id'))) {
                throw new \InvalidArgumentException("Product Id is required.");
            }

            $contact = customer(true);

            Wishlist::create([
                'customer_id' => $contact->customer_id,
                'contact_id' => $contact->id,
                'product_id' => $request->input('product_id')
            ]);


            return response()->json([
                'status' => true,
                'message' => 'New Item added to Wishlist'
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'type' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    public function remove($product_id): JsonResponse
    {
        try {

            $contact = customer(true);

            /**
             * @var $wishlist Wishlist
             */
            $wishlist = Wishlist::where([
                'customer_id' => $contact->customer_id,
                'contact_id' => $contact->id,
                'product_id' => $product_id
            ])->first();

            $wishlist->deleteQuietly();

            return response()->json([
                'status' => true,
                'message' => 'Item removed from Wishlist'
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'type' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function update(Wishlist $wishlist): JsonResponse
    {
        try {

            if (empty($request->input('product_id'))) {
                throw new \InvalidArgumentException("Product Id is required.");
            }

            $contact = customer(true);

            Wishlist::create([
                'customer_id' => $contact->customer_id,
                'contact_id' => $contact->id,
                'product_id' => $request->input('product_id')
            ]);


            return response()->json([
                'status' => true,
                'message' => 'New Item added to Wishlist'
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'type' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function check($product_id)
    {
        $contact = customer(true);

        $wishlist = Wishlist::where('customer_id', $contact->customer_id)
            ->where('contact_id', $contact->id)
            ->where('product_id', $product_id)
            ->limit(1)
            ->get();

        return response()->json([
            'exists' => $wishlist->isNotEmpty(),
            'data' => $wishlist->first()
        ]);
    }

    public function notification(WishlistNotificationRequest $request): JsonResponse
    {
        try {

            $contact = customer(true);

            $notify = $request->boolean('notify', true);

            $wishlistItem = Wishlist::where('customer_id', '=', $contact->customer_id)
                ->where('contact_id', '=', $contact->id)
                ->where('product_id', '=', $request->input('product_id'))
                ->first();

            if (!$wishlistItem) {
                throw new \Exception("Wishlist Item doesn't exist");
            }

            if (!$wishlistItem->update(['notify' => $notify])) {
                throw new \Exception(__('Wishlist notification status update failed.'));
            }

            return $this->apiResponse(true, __('Wishlist notification status changed successfully.'));

        } catch (\Exception $exception) {
            return $this->apiResponse(false, $exception->getMessage(), 500);
        }
    }
}
