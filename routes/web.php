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
Route::get('/','PagesController@mainPage');

Route::get('/add-to-cart/{id}', [
    'uses' => 'PagesController@addToCart',
    'as' => 'product.addToCart'
    ]);

Route::get('/update-quantity/{quantity}/{id}', [
    'uses' => 'PagesController@updateQuantity',
    'as' => 'product.updateQuantity'
]);

Route::get('/remove-from-cart/{id}', [
    'uses' => 'PagesController@removeFromCart',
    'as' => 'product.removeFromCart'
]);

Route::get('/checkout/{mail}', [
    'uses' => 'PagesController@checkout',
    'as' => 'product.checkout'
]);

Route::get('/product/{id}/{status}',[
    'uses' => 'ProductsController@create',
    'as' => 'product.create'
])->middleware('login');

Route::post('/stored/{id}/{status}',[
    'uses' => 'ProductsController@store',
    'as' => 'product.store'
])->middleware('login');

Route::get('/delete/{id}',[
    'uses' => 'ProductsController@destroy',
    'as' => 'product.delete'
]);

Route::get('/update/{id}',[
    'uses' => 'ProductsController@update',
    'as' => 'product.update'
]);

Route::get('/cart','PagesController@cart');

Route::get('/index', 'PagesController@index');

Route::get('/admin', 'ProductsController@index')->middleware('login');

Route::post('/login', function(){
    return view('auth.login');
});

Route::post('/login-attempt',[
    'uses' => 'PagesController@login',
    'as' => 'page.login'
]);

Route::get('/logout', 'PagesController@logout');