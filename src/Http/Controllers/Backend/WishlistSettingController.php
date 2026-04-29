<?php

namespace Amplify\Wishlist\Http\Controllers\Backend;

use Amplify\System\Abstracts\BackpackCustomCrudController;
use Amplify\System\Backend\Models\SystemConfiguration;
use Amplify\System\Backend\Traits\SettingOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class WishlistSettingController
 *
 * @property-read CrudPanel $crud
 */
class WishlistSettingController extends BackpackCustomCrudController
{
    use SettingOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(SystemConfiguration::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/wishlist-setting');
        CRUD::setEntityNameStrings('wishlist-setting', 'Wishlist Settings');
    }

    /**
     * Configure the setting group name. Apply settings to all operations.
     */
    public function getSettingName(): string
    {
        return 'wishlist';
    }
}
