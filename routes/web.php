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

Route::get('/', 'PagesController@index');

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

Route::get('/create/{id}',[
    'uses' => 'PostsController@edit',
    'as' => 'product.edit'
]);

Route::get('/delete/{id}',[
    'uses' => 'PostsController@destroy',
    'as' => 'product.delete'
]);

Route::get('/cart','PagesController@cart');

Route::get('/index', 'PagesController@index');

Route::resource('/posts','PostsController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
