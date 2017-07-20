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


Route::get('/add-to-cart/{id}', [
    'uses' => 'PagesController@addToCart',
    'as' => 'product.addToCart'
    ]);

Route::get('/update-quantity/{id}', [
    'uses' => 'PagesController@updateQuantity',
    'as' => 'product.updateQuantity'
]);

Route::get('/remove-from-cart/{id}', [
    'uses' => 'PagesController@removeFromCart',
    'as' => 'product.removeFromCart'
]);

Route::get('/checkout', [
    'uses' => 'PagesController@checkout',
    'as' => 'product.checkout'
]);

Route::get('/product/{id}',[
    'uses' => 'ProductsController@edit',
    'as' => 'product.edit'
]);

Route::get('/product',[
    'uses' => 'ProductsController@create',
    'as' => 'product.create'
]);

Route::get('/delete/{id}',[
    'uses' => 'ProductsController@destroy',
    'as' => 'product.delete'
]);

Route::get('/cart','PagesController@cart');

Route::get('/index', 'PagesController@index');

Route::resource('/admin','ProductsController');

Route::get('/login', function(){
    return view('auth.login');
});

Route::post('/login-attempt',[
    'uses' => 'PagesController@login',
    'as' => 'page.login'
]);

Route::get('/logout', 'PagesController@logout');