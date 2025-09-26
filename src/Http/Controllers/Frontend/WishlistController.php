<?php

namespace Amplify\Wishlist\Http\Controllers\Frontend;

use Amplify\Frontend\Http\Requests\FavoriteListRequest;
use Amplify\Frontend\Http\Requests\UpdateOrderListRequest;
use Amplify\Frontend\Traits\HasDynamicPage;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FavoriteListRequest $request): JsonResponse
    {
        try {
            $inputs = $request->all();

            $orderListItem = new OrderListItem([
                'product_id' => $inputs['product_id'],
                'qty' => $inputs['product_qty'],
                'list_id' => $inputs['list_id'],
            ]);

            if ($inputs['list_id'] == null) {
                $orderList = OrderList::create([
                    'name' => $inputs['list_name'],
                    'list_type' => $inputs['list_type'],
                    'description' => $inputs['list_desc'],
                    'contact_id' => customer(true)->getKey(),
                    'customer_id' => customer()->getKey(),
                ]);
            } else {
                $orderList = OrderList::find($inputs['list_id']);
            }

            if ($orderList instanceof OrderList) {
                $orderList->orderListItems()->save($orderListItem);
                $orderList->touch();
            }

            return response()->json([
                'type' => 'success',
                'status' => true,
                'message' => ($inputs['list_id'] != null) ? 'New Item added to Favorite list' : 'Favorite List created and Added this item.',
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'type' => 'error',
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!in_array(true, [customer(true)->can('favorites.manage-global-list'), customer(true)->can('favorites.manage-personal-list')])) {
            abort(403);
        }
        $this->loadPageByType('favourite_detail');

        return $this->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws \ErrorException
     */
    public function edit(string $id): string
    {
        if (!in_array(true, [customer(true)->can('favorites.manage-personal-list'), customer(true)->can('favorites.manage-personal-list')])) {
            abort(403);
        }
        $this->loadPageByType('favourite_edit');

        return $this->render();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderListRequest $request, string $id): RedirectResponse
    {
        if (!in_array(true, [customer(true)->can('favorites.manage-personal-list'), customer(true)->can('favorites.manage-personal-list')])) {
            abort(403);
        }
        $orderList = OrderList::findOrFail($id);

        $orderList->fill($request->validated());

        if (!$orderList->save()) {
            session()->flash('error', 'Favorite List updated field.');

            return redirect()->back();
        }
        session()->flash('success', 'Favorite List updated successfully.');

        return redirect()->route('frontend.favourites.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function syncProduct(Request $request, OrderList $orderList)
    {
        try {
            $action = $request->input('action', 'add');

            $products = Arr::wrap($request->input('product'));

            if ($action == 'remove') {
                OrderListItem::whereIn('product_id', $products)->get()->each(function ($item) {
                    if (!$item->delete()) {
                        throw new \Exception('Failed to remove item favorite list');
                    }
                });

                return response()->json([
                    'type' => 'warning',
                    'status' => true,
                    'message' => 'Item removed from favorite list.',
                ]);
            }

            if ($action == 'add') {
                $entries = [];
                foreach ($products as $product) {
                    $entries[] = new OrderListItem([
                        'product_id' => $product,
                        'qty' => 1,
                    ]);
                }

                if ($orderList->orderListItems()->createMany($entries)) {
                    return response()->json([
                        'type' => 'success',
                        'status' => true,
                        'message' => 'Product(s) Added to favorite list.',
                    ]);
                }
            }

        } catch (\Exception $exception) {
            return response()->json(['type' => 'error',
                'status' => false,
                'message' => $exception->getMessage()]);
        }
    }

    /**
     * This will delete the saved order list item
     *
     * @param OrderList $favourite
     */
    public function destroy(Request $request, $favouriteItem): RedirectResponse
    {
        if (!in_array(true, [customer(true)->can('favorites.manage-global-list'), customer(true)->can('favorites.manage-personal-list')])) {
            abort(403);
        }
        try {
            $item = OrderList::find($favouriteItem);
            $item->delete();
            Session::flash('success', 'You have successfully deleted the Favorite List!');
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something went wrong...');
        }

        return back();
    }

    /**
     * This will delete the saved order list item
     *
     * @param OrderListItem $favourite
     */
    public function destroyOrderListItem(Request $request, $favouriteItem): RedirectResponse
    {
        if (!in_array(true, [customer(true)->can('favorites.manage-global-list'), customer(true)->can('favorites.manage-personal-list')])) {
            abort(403);
        }

        try {
            $item = OrderListItem::find($favouriteItem);
            $item->delete();
            Session::flash('success', 'You have successfully deleted the Favorite List Item!');
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something went wrong...');
        }

        return back();
    }
}
