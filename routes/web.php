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

        Route::get('logout', 'login\AdminController@logout')->name('logout');
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        Route::group(['namespace' => 'User'], function () {
            Route::resource('user','UserController');
            Route::resource('agencys','UserAgencyController');
        });

        Route::group(['namespace' => 'News'], function () {

            //NEWS CATEGORY
            Route::resource('news_category','NewsCategoryController');
            Route::get('del-cate-news/{id}', 'NewsCategoryController@destroy')->name('news.category.del');
            Route::post('delall-news-category', 'NewsCategoryController@delMulti')->name('news.category.delMulti');
            Route::get('add-cate-news/{lang}/{id}', 'NewsCategoryController@createLang')->name('news.category.add.lang');
            Route::post('add-cate-news/{lang}/{id}', 'NewsCategoryController@storeLang');

            //NEWS
            Route::resource('news','NewsController');
            Route::get('del-news/{id}', 'NewsController@destroy')->name('news.del');
            Route::post('delall-news', 'NewsController@delMulti')->name('news.delMulti');
            Route::get('add-news/{lang}/{id}', 'NewsController@createLang')->name('news.add.lang');
            Route::post('add-news/{lang}/{id}', 'NewsController@storeLang');

        });

        Route::group(['namespace' => 'Page'], function () {
            Route::resource('pages','PagesController');
            Route::get('add-page/{lang}/{id}', 'PagesController@createLang')->name('pages.add.lang');
            Route::post('add-page/{lang}/{id}', 'PagesController@storeLang');
            Route::get('del-page/{id}', 'PagesController@destroy')->name('pages.del');
            Route::post('list-page', 'PagesController@delMulti')->name('pages.delMulti');

        });
        Route::group(['namespace' => 'Video'], function () {
            Route::resource('videos','VideosController');
            Route::get('add-videos/{lang}/{id}', 'VideosController@createLang')->name('videos.add.lang');
            Route::post('add-videos/{lang}/{id}', 'VideosController@storeLang');
            Route::get('del-videos/{id}', 'VideosController@destroy')->name('videos.del');
            Route::post('list-videos', 'VideosController@delMulti')->name('videos.delMulti');
        });

        Route::group(['namespace' => 'Customer'], function () {
            Route::resource('customer','CustomerController');
            Route::get('del-customer/{id}', 'CustomerController@destroy')->name('customer.del');
            Route::post('list-customer', 'CustomerController@delMulti')->name('customer.delMulti');
        });

        Route::group(['namespace' => 'Support'], function () {
            Route::resource('support','SupportController');
            Route::get('del-support/{id}', 'SupportController@destroy')->name('support.del');
            Route::post('list-support', 'SupportController@delMulti')->name('support.delMulti');
        });

        Route::group(['namespace' => 'Contact'], function () {
            Route::resource('contact','ContactController');

            Route::post('list-contact', 'ContactController@delMulti')->name('contact.delMulti');
            Route::get('del-contact/{id}', 'ContactController@destroy')->name('contact.del');
        });

        Route::group(['namespace' => 'Alias'], function () {
            Route::resource('alias','AliasController');
        });

        Route::group(['namespace' => 'SiteSetting'], function () {
            Route::get('site-setting', 'SiteOptionController@index')->name('site.setting');
            Route::post('site-setting', 'SiteOptionController@post')->name('site.setting');
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

        Route::group(['namespace' => 'Media'], function () {
            Route::get('del-media/{id}', 'MediaController@destroy')->name('media.del');
            Route::post('list-media', 'MediaController@delMulti')->name('media.delMulti');
            Route::resource('media','MediaController');
        });

        Route::group(['namespace' => 'Gallery'], function () {
            Route::get('add-gallerys/{lang}/{id}', 'GalleryController@createLang')->name('gallerys.add.lang');
            Route::post('add-gallerys/{lang}/{id}', 'GalleryController@storeLang');
            Route::get('del-gallerys/{id}', 'GalleryController@destroy')->name('gallerys.del');
            Route::post('list-gallerys', 'GalleryController@delMulti')->name('gallerys.delMulti');
            Route::resource('gallerys','GalleryController');
        });

        Route::group(['namespace' => 'Menu'], function () {
            Route::post('ajax-add-menu', 'MenuController@ajax_add_menu')->name('ajax.add.menu');
            Route::get('menus/position/{position}', 'MenuController@position')->name('change.position.menu');
            Route::resource('menus','MenuController');
        });


        Route::group(['namespace' => 'Product'], function () {

            Route::get('del-product/{id}', 'ProductController@destroy')->name('products.del');
            Route::post('delall-product', 'ProductController@delMulti')->name('products.delMulti');
            Route::get('add-product/{lang}/{id}', 'ProductController@createLang')->name('products.add.lang');
            Route::post('add-product/{lang}/{id}', 'ProductController@storeLang');
            Route::get('stock-card','ProductController@stock')->name('products.stock');
            Route::resource('products','ProductController');

            Route::get('del-product-category/{id}', 'ProductCategoryController@destroy')->name('product.category.del');
            Route::post('delall-product-category', 'ProductCategoryController@delMulti')->name('product.category.delMulti');
            Route::get('add-product-category/{lang}/{id}', 'ProductCategoryController@createLang')->name('product.category.add.lang');
            Route::post('add-product-category/{lang}/{id}', 'ProductCategoryController@storeLang');
            Route::resource('product_categorys','ProductCategoryController');

            Route::resource('attributes','AttributeController');
            Route::resource('attribute_categorys','AttributeCategoryController');

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
            Route::get('ajax/session/{id}','ImportController@ajax')->name('ajax.session');
            Route::resource('imports','ImportController');
        });

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Route::get('ajax/data_sort', 'AjaxController@getEditDataSort')->name('ajax.data.sort');
        Route::get('ajax/data_public', 'AjaxController@getEditDataPublic')->name('ajax.data.public');
        Route::get('ajax/data_status', 'AjaxController@getEditDataStatus')->name('ajax.data.status');

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

Route::group(['prefix' => 'contact'],function(){
    Route::get('contact.html', 'ContactController@index')->name('contact.index');
    Route::post('contact.html', 'ContactController@post');

    Route::get('lien-he.html', 'ContactController@index');
    Route::post('lien-he.html', 'ContactController@post');
});

Route::get('/', 'HomeController@index')->name('home');
Route::get('{alias}.html', 'HomeController@getAlias')->name('alias');

Route::get('tag/{alias}', 'TagController@index')->name('tag.show');
Route::get('video.html', 'VideoController@index')->name('video.index');
Route::get('gallery.html', 'GalleryController@index')->name('gallery.index');
Route::get('search', 'SearchController@index');
