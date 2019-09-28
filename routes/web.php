<?php

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

Route::group(['middleware' => ['guest']], function(){
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    
    Route::get('/inicio', function () {
        return view('store.category');
    })->name('main');
    
    //Notifications
    Route::post('/notification/get', 'NotificationController@get');
    Route::get('/dashboard', 'DashboardController');

    Route::group(['middleware' => ['Grocer']], function(){
        Route::get('/categoria', 'CategoryController@index');
        Route::post('/categoria', 'CategoryController@store');
        Route::put('/categoria/{id}', 'CategoryController@update');
        Route::delete('/categoria/{id}', 'CategoryController@destroy');
        Route::put('/categoria/activar/{id}', 'CategoryController@recover');
        Route::get('/categoria/selectCategoria', 'CategoryController@selectCategory');

        Route::get('/articulo', 'ProductController@index');
        Route::post('/articulo', 'ProductController@store');
        Route::put('/articulo/{id}', 'ProductController@update');
        Route::delete('/articulo/{id}', 'ProductController@destroy');
        Route::put('/articulo/activar/{id}', 'ProductController@recover');
        Route::get('/articulo/buscar', 'ProductController@search');
        Route::get('/articulo/listaArticulo', 'ProductController@listProducts');
        Route::get('/srticulo/buscarArticuloVenta', 'ProductController@searchProductSale');
        Route::get('/articulo/listaArticuloVenta', 'ProductController@listProductsAvailable');
        Route::get('/articulo/listaArticuloPdf', 'ProductController@productsPdf')->name('list_product_pdf');

        Route::resource('/proveedor', 'ProviderController', ['except' => ['create', 'show', 'edit', 'destroy']]);
        Route::get('/proveedor/selectProveedor', 'ProviderController@selectProvider');

        Route::resource('/compra', 'PurchaseController', ['except' => ['create', 'show', 'edit', 'update']]);

    }); 
    
    Route::group(['middleware' => ['Seller']], function(){
        Route::resource('/cliente', 'ClientController', ['except' => ['create', 'show', 'edit', 'destroy']]);
        Route::get('/venta/comprobante/{id}', 'SaleController@salePdf')->name('sale_pdf');
        Route::resource('/venta', 'SaleController', ['except' => ['create', 'edit', 'update']]);
        Route::get('/cliente/selectCliente', 'ClientController@selectClient');
       
    });

    Route::group(['middleware' => ['Administrator']], function(){

        Route::resource('/categoria', 'CategoryController');
        Route::put('/categoria/activar/{id}', 'CategoryController@recover');
        Route::get('/categoria/selectCategoria', 'CategoryController@selectCategory');

        Route::get('/articulo/buscarArticuloVenta', 'ProductController@searchProductSale');
        Route::get('/articulo/listaArticuloVenta', 'ProductController@listProductsAvailable');
        Route::get('/articulo/buscar', 'ProductController@search');
        Route::get('/articulo/listaArticulo', 'ProductController@listProducts');
        Route::get('/articulo/listaArticuloPdf', 'ProductController@productsPdf')->name('list_product_pdf');
        Route::resource('/articulo', 'ProductController');
        
        Route::resource('/cliente', 'ClientController', ['except' => ['create', 'show', 'edit', 'destroy']]);
        Route::get('/cliente/selectCliente', 'ClientController@selectClient');

        Route::resource('/proveedor', 'ProviderController', ['except' => ['create', 'show', 'edit', 'destroy']]);
        Route::get('/proveedor/selectProveedor', 'ProviderController@selectProvider');

        Route::resource('/compra', 'PurchaseController', ['except' => ['create', 'edit','update']]);

        Route::get('/venta/comprobante/{id}', 'SaleController@salePdf')->name('sale_pdf');
        Route::resource('/venta', 'SaleController', ['except' => ['create', 'edit', 'update']]);

        Route::resource('/user', 'UserController');
        Route::put('/user/activar/{id}', 'UserController@recover');

        Route::get('/rol', 'RoleController@index');
        Route::get('/rol/selectRol', 'RoleController@selectRol');

    });
  
});

//Route::get('/home', 'HomeController@index')->name('home');
