<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//include 'Mobile_Detect.php';
//$detect = new Mobile_Detect;


Auth::routes();


Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {

    Route::get('admincp', 'Login\AdminController@getLogin')->name('login');
    Route::post('admincp', 'Login\AdminController@postLogin');

    Route::get('admin/first/user', 'login\AdminController@getFirstUse')->name('first.user');
    Route::post('admin/first/user', 'login\AdminController@postFirstUse')->name('first.user');

    Route::group(['prefix' => 'admin'], function () {
        //dashboard
        Route::get('logout', 'login\AdminController@logout')->name('logout');
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        //categories
        Route::get('/remove/categories/{id}', 'CategoryController@remove')->name('categories.remove');
        Route::post('/delete/categories', 'CategoryController@delete')->name('categories.delete');
        Route::post('/add/categories/{lang}/{id}', 'CategoryController@add')->name('categories.add');
        Route::resource('categories','CategoryController');

        //pages
        Route::get('/posts/pages/lang/{lang}/{id}', 'PageController@lang')->name('posts.pages.lang');
        Route::resource('/posts/pages','PageController',['as' => 'posts']);
        //posts categories
        Route::get('/posts/categories/lang/{lang}/{id}', 'PostCategoryController@lang')->name('posts.categories.lang');
        Route::resource('/posts/categories','PostCategoryController',['as' => 'posts']);
        //posts
        Route::get('/lang/posts/{lang}/{id}', 'PostController@lang')->name('posts.lang');
        Route::post('/add/posts/{lang}/{id}', 'PostController@add')->name('posts.add');
        Route::get('/remove/posts/{id}', 'PostController@remove')->name('posts.remove');
        Route::get('/delete/posts', 'PostController@delete')->name('posts.delete');
        Route::resource('posts','PostController');


        //products categories
        Route::get('/products/categories/lang/{lang}/{id}', 'ProductCategoryController@lang')->name('products.categories.lang');
        Route::resource('/products/categories','ProductCategoryController',['as' => 'products']);
        //videos
        Route::get('add/products/videos/{lang}/{id}', 'VideoController@lang')->name('products.videos.lang');
        Route::resource('/products/videos','VideoController',['as' => 'products']);
        //galeries
        Route::get('add/products/galleries/{lang}/{id}', 'GalleryController@lang')->name('products.galleries.lang');
        Route::resource('/products/galleries','GalleryController',['as' => 'products']);
        //products
        Route::get('stock/card','ProductController@stock')->name('products.stock');
        Route::get('remove/products/{id}', 'ProductController@remove')->name('products.remove');
        Route::post('delete/products', 'ProductController@delete')->name('products.delete');
        Route::post('add/products/{lang}/{id}', 'ProductController@add')->name('products.add');
        Route::get('add/products/{lang}/{id}', 'ProductController@lang')->name('products.lang');
        Route::resource('products','ProductController');

        Route::group(['namespace' => 'Product'], function () {

            Route::resource('attributes','AttributeController');
            Route::resource('attribute_categorys','AttributeCategoryController');

        });
        //Photos
        Route::resource('photos','PhotoController');

        //Menus
        Route::post('/add/menus', 'MenuController@add')->name('ajax.add.menu');
        Route::get('/position/menus/{position}', 'MenuController@position')->name('change.position.menu');
        Route::resource('menus','MenuController');

        //supports
        Route::resource('/supports/customers','CustomerController',['as' => 'supports']);
        Route::get('remove/supports/{id}', 'SupportController@remove')->name('supports.remove');
        Route::post('delete/supports', 'SupportController@delete')->name('supports.delete');
        Route::resource('supports','SupportController');

        Route::group(['namespace' => 'User'], function () {
            Route::resource('user','UserController');
            Route::resource('agencys','UserAgencyController');
        });

        Route::group(['namespace' => 'Contact'], function () {
            Route::resource('contact','ContactController');

            Route::post('list-contact', 'ContactController@delMulti')->name('contact.delMulti');
            Route::get('del-contact/{id}', 'ContactController@destroy')->name('contact.del');
        });

        Route::get('settings','SettingController@index')->name('settings');
        Route::post('settings','SettingController@update');

        Route::group(['namespace' => 'Alias'], function () {
            Route::resource('alias','AliasController');
        });

        Route::group(['namespace' => 'Source'], function () {
            Route::resource('source','SourceController');
            Route::get('ajax-load-source', 'SourceController@load')->name('ajax.load.source');
            Route::post('ajax-push-source', 'SourceController@push')->name('ajax.push.source');
        });

        Route::group(['namespace' => 'Module'], function () {
            Route::get('modules/action/{table}', 'ModulesController@actionIndex')->name('action.module.index');
            Route::get('modules/action/{table}/add', 'ModulesController@createAction')->name('action.module.add');
            Route::post('modules/action/{table}/add', 'ModulesController@storeAction');
            Route::get('modules/action/{table}/{id}/edit', 'ModulesController@editAction')->name('action.module.edit');
            Route::post('modules/action/{table}/{id}/edit', 'ModulesController@updateAction');
            Route::get('modules/action/{table}/{id}/del', 'ModulesController@detroyAction')->name('action.module.destroy');

            Route::resource('modules','ModulesController');
            Route::resource('systems','SystemsController');

        });



        Route::group(['namespace' => 'Order'], function () {
            Route::get('orders/update/session/{id}/{amount}/{price}/{revenue}','OrderController@updateItemSession')->name('orders.update.session');
            Route::get('orders/destroy/session/{id}','OrderController@destroyItemSession')->name('orders.destroy.session');
            Route::get('orders/get/session/{id}','OrderController@getItemSession')->name('orders.get.session');

            Route::get('orders/add/cart/{order}/{id}/{amount}/{price}/{revenue}','OrderController@addItemCart')->name('orders.add.cart');
            Route::get('orders/destroy/cart/{order}/{rowId}','OrderController@destroyItemCart')->name('orders.destroy.cart');
            Route::get('orders/get/cart/{order}/{rowId}','OrderController@getItemCart')->name('orders.get.cart');
            Route::get('orders/update/cart/{order}/{rowId}/{amount}/{price}/{revenue}','OrderController@updateItemCart')->name('orders.update.cart');

            Route::resource('orders','OrderController');

        });
        Route::group(['namespace' => 'Import'], function () {
            Route::post('update/session/{id}','ImportController@updateSession')->name('update.session');
            Route::get('destroy/session/{id}','ImportController@destroySession')->name('destroy.session');
            Route::get('get/session/{id}','ImportController@ajax')->name('ajax.session');
            Route::resource('imports','ImportController');
        });
        Route::get('comments/{type}','CommentController@list')->name('comments.list');
        Route::get('comments/{type}/{id}','CommentController@detail')->name('comments.detail');
        Route::get('comments/destroys/{type}/{id}','CommentController@destroys')->name('comments.destroys');
        Route::resource('comments','CommentController');
        Route::resource('reports','ReportController');
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Route::get('ajax/data/sort', 'AjaxController@getEditDataSort')->name('ajax.data.sort');
        Route::get('ajax/data/public', 'AjaxController@getEditDataPublic')->name('ajax.data.public');
        Route::get('ajax/data/status', 'AjaxController@getEditDataStatus')->name('ajax.data.status');

        Route::get('ajax/menu-sort', 'AjaxController@getEditMenuSort')->name('ajax.menu.sort');

        //Import
        Route::get('ajax/choise-agency/{id}/{product}', 'AjaxController@getImportAgency')->name('ajax.choise.agency');
        Route::get('ajax/choise-product/{id}/{agency}', 'AjaxController@getImportProduct')->name('ajax.choise.product');
        Route::get('ajax/import-product/{id}/{amount}/{price}', 'AjaxController@setImportProduct')->name('ajax.import.product');
        Route::get('ajax/get-item-import/{rowId}','AjaxController@getItemImport')->name('ajax.get.item.import');
        Route::get('ajax/destroy-item-import/{rowId}','AjaxController@setDestroyItemImport')->name('ajax.destroy.item.import');
        Route::get('ajax/update-item-import/{rowId}/{amount}/{price}','AjaxController@setUpdateItemImport')->name('ajax.update.item.import');


        //End Import

        //Export
        Route::get('ajax/export-product/{id}/{amount}/{price}/{revenue}', 'AjaxController@setExportProduct')->name('ajax.export.product');
        Route::get('ajax/choise-export-product/{id}/{user}', 'AjaxController@getExportProduct')->name('ajax.choise.export.product');
        Route::get('ajax/choise-user/{id}/{product}', 'AjaxController@getExportUser')->name('ajax.choise.user');
        Route::get('ajax/update-product/{id}/{amount}/{price}/{checkout}/{agency}/{customer}','AjaxController@setUpdateProduct')->name('ajax.update.product');

        Route::get('ajax/get-item-export/{rowId}','AjaxController@getItemExport')->name('ajax.get.item.export');
        Route::get('ajax/destroy-item-export/{rowId}','AjaxController@setDestroyItemExport')->name('ajax.destroy.item.export');
        Route::get('ajax/update-item-export/{rowId}/{amount}/{price}/{revenue}','AjaxController@setUpdateItemExport')->name('ajax.update.item.export');

        Route::get('ajax/get-product-update/{id}','AjaxController@getProductUpdate')->name('ajax.get.product.export');

        Route::get('ajax/get-data-print/{customer}','AjaxController@getDataPrint')->name('ajax.get.data.print');

        Route::get('ajax/get-revenue/session/{id}/{amount}/{price}','AjaxController@getRevenueSession')->name('ajax.get.revenue');
        Route::get('ajax/get-revenue-old/session/{id}/{amount}/{price}','AjaxController@getRenvenueAfter')->name('ajax.get.revenue.old');

        //End Export

        Route::get('ajax/position-photo/{json}','AjaxController@setPositionPhoto')->name('ajax.set.position.photo');

        Route::get('ajax/edit-alt/{id}/{alt}','AjaxController@setAltPhoto')->name('ajax.set.alt');
        Route::get('ajax/get-alt/{id}','AjaxController@getAltPhoto')->name('ajax.get.alt');

        Route::post('ajax/upload-photo/{id}/{type}','AjaxController@uploadPhoto')->name('ajax.upload.photo');
        Route::get('ajax/remove-photo/{id}','AjaxController@removePhoto')->name('ajax.remove.photo');

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Route::group(['namespace' => 'lang'], function () {
            Route::resource('lang','LangController');
            Route::get('change-lang/{lang}', 'LangController@change')->name('change.lang');
            Route::get('active-lang/{id}','LangController@active')->name('active.lang');
        });
    });

});

Route::fallback(function(){
    return abort(404);
});
/////////////////////////////////////////////////////////////
Route::group(['as'=>'user.'], function () {
    Route::get('login', 'UserController@getLogin')->name('login');
    Route::post('login', 'UserController@postLogin');
    Route::get('logout', 'UserController@logout')->name('logout');
    Route::get('register', 'UserController@getRegister')->name('register');
    Route::post('register', 'UserController@postRegister')->name('register');
    Route::get('profile', 'UserController@getInfo')->name('profile');
    Route::post('profile', 'UserController@postEditUser');
    Route::post('forget-user', 'UserController@postForgetUser');
    Route::get('forget-user', 'UserController@getForgetUser')->name('forget');
    Route::get('password/reset', 'UserController@getPasswordReset')->name('reset');

    Route::get('auth/{provider}/login','UserController@redirect')->name('social');
    Route::get('{provider}/callback','UserController@callback')->name('social.callback');
});
Route::group(['as' => 'cart.'], function () {
    Route::get('shopping-cart', 'ShoppingCartController@index');
    Route::get('checkout', 'ShoppingCartController@checkout');
    Route::post('checkout', 'ShoppingCartController@payment');
    Route::get('cart-destroy', 'ShoppingCartController@destroy');
    Route::get('cart-remove/{rowid}', 'ShoppingCartController@remove');
});
Route::group(['prefix' => 'ajax','as' => 'ajax.'], function () {
    Route::get('add-cart/{id}', 'AjaxController@addShoppingCart');
    Route::get('update-cart/{rowId}/{num}', 'AjaxController@updateShoppingCart');
    Route::get('remove-cart/{rowId}', 'AjaxController@removeItemShoppingCart');
    Route::get('destroy-cart', 'AjaxController@destroyShoppingCart');
    Route::get('lang/{alias}/{lang}', 'AjaxController@change_lang')->name('change.lang');
});

Route::get('lien-he.html', 'ContactController@index');
Route::get('contact.html', 'ContactController@index')->name('contact.index');
Route::post('contact', 'ContactController@store')->name('send.contact');

Route::get('/', 'HomeController@index')->name('home');
Route::get('{alias}.html', 'HomeController@getAlias')->name('alias');

Route::get('tag/{alias}', 'TagController@index')->name('tag.show');
Route::get('video.html', 'VideoController@index')->name('video.index');
Route::get('gallery.html', 'GalleryController@index')->name('gallery.index');
Route::get('search', 'SearchController@index');

Route::resource('comments','CommentController');
