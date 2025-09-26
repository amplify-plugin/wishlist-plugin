<?php

namespace Amplify\Wishlist\Http\Controllers\Backend;

use Amplify\System\Abstracts\BackpackCustomCrudController;
use Amplify\Wishlist\Http\Requests\WishlistRequest;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

/**
 * Class WishlistCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WishlistCrudController extends BackpackCustomCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\Amplify\Wishlist\Models\Wishlist::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/wishlist');
        CRUD::setEntityNameStrings('wishlist', 'wishlists');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Filtering with customer
        CRUD::addFilter(
            [
                'name' => 'customer_id',
                'type' => 'select2_ajax',
                'label' => 'Customer',
                'placeholder' => 'Type Name, Code, Email, Phone',
                'method' => 'POST',
                'select_attribute' => 'display_name',
            ],
            backpack_url('contact/fetch/customer'),
            function ($value) { // if the filter is active
                $this->crud->query->where('customer_id', $value);
            }
        );

        CRUD::addFilter(
            [
                'name' => 'contact_id',
                'type' => 'select2_ajax',
                'label' => 'Contact',
                'placeholder' => 'Type Name, Email, Phone',
                'method' => 'POST',
                'select_attribute' => 'name',
            ],
            backpack_url('wishlist/fetch/contact'),
            function ($value) { // if the filter is active
                $this->crud->query->where('contact_id', $value);
            }
        );

        CRUD::column('id')->label('#');
        CRUD::column('contact_id')->type('relationship')->label('Contact');
        CRUD::column('product_id')->type('relationship')->label('Product');
        CRUD::column('remove_from_cart')->type('boolean');
        CRUD::column('last_notified_at')->type('datetime');
        CRUD::column('created_at')->type('datetime');
        CRUD::column('updated_at')->type('datetime');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(WishlistRequest::class);

        CRUD::addField([
            'label' => 'Customer', // Table column heading
            'type' => 'select2_from_ajax',
            'name' => 'customer_id', // the column that contains the ID of that connected entity;
            'attribute' => 'display_name', // foreign key attribute that is shown to user
            'data_source' => backpack_url('contact/fetch/customer'),
            'method' => 'POST',
            'default' => old('customer_id', $this->crud->entry->customer_id ?? null),
        ]);

        CRUD::addField([
            'label' => 'Contact', // Table column heading
            'type' => 'select2_from_ajax',
            'dependencies' => ['customer_id'],
            'name' => 'contact_id', // the column that contains the ID of that connected entity;
            'data_source' => backpack_url('wishlist/fetch/contact'),
            'method' => 'POST',
            'include_all_form_fields' => true,
            'default' => old('contact_id', $this->crud->entry->contact_id ?? null),
        ]);

        CRUD::addField([
            'label' => 'Product',
            'type' => 'easyask_product_search',
            'name' => 'product_id',
            'attribute' => 'display_name',
            'placeholder' => 'Select Product',
            'delay' => 200,
            'ajax' => true,
            'entity' => 'product',
            'default' => old('product_id', $this->crud->entry->product_id ?? null),
        ]);

        CRUD::field('remove_from_cart')->type('boolean')->label('Remove Item when added to cart?');
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function fetchContact()
    {
        $inputs = backpack_form_input();

        return $this->fetch([
            'model' => \Amplify\System\Backend\Models\Contact::class,
            'paginate' => 10,
            'searchOperator' => 'LIKE',
            'query' => function ($model) use ($inputs) {
                return $model->select('id', 'name')
                    ->when(!empty($inputs['customer_id']), fn($q) => $q->where('customer_id', $inputs['customer_id']));
            },
        ]);
    }
}
