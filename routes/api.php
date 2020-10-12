<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::post('sendOtp', 'api\AuthController@sendOtp');
Route::post('resendOtp', 'api\AuthController@resendOtp');
Route::post('login', 'api\AuthController@login');//used to verify otp and login.
Route::get('logout', 'api\AuthController@logout');
Route::post('forgotPassword', 'api\UserController@doForgotPassword');
Route::post('register', 'api\UserController@createUser');
Route::post('search', 'api\UserController@search');
Route::get('categories', 'api\CategoryController@getAllCategories');

Route::group(['middleware' => ['auth:api']], function () {
    
    Route::get('appState', 'api\UserController@getAppStateData');
    Route::get('markAllRead', 'api\NotificationController@markread');

    // subcategories
    Route::get('subCategories', 'api\CategoryController@allSubcategories');
    Route::get('getsubCategories/{category_id}', 'api\CategoryController@subcategories');
    
    //verticals
    Route::get('getVerticals/{subcategory_id}', 'api\CategoryController@verticals');
    
    // product
    Route::post('addProduct', 'api\ProductController@addProduct');
    Route::post('editProduct', 'api\ProductController@editProduct');
    Route::get('products', 'api\ProductController@index');
    Route::get('product/{id}', 'api\ProductController@productDetail');
    Route::delete('product/{id}', 'api\ProductController@deleteProduct');

    // cart
    Route::get('cart', 'api\CartController@index');
    Route::post('addToCart', 'api\CartController@addToCart');
    Route::delete('cart/delete/{id}', 'api\CartController@deleteFromCart');
    Route::post('removeFromCart', 'api\CartController@decreaseCartItemQuantity');

    // order
    Route::get('placeOrder', 'api\OrderController@placeOrder');
    Route::get('placedOrders', 'api\OrderController@placedOrders');
    Route::get('receivedOrders', 'api\OrderController@receivedOrders');
    Route::get('order/{id}', 'api\OrderController@orderDetail');
    Route::get('receivedOrder/{id}', 'api\OrderController@receivedOrderDetail');
    Route::get('cancelOrder/{id}', 'api\OrderController@cancelOrder');
    Route::get('acceptOrder/{id}', 'api\OrderController@acceptOrder');

    // favorite
    Route::post('addFavorite', 'api\UserController@addFavorite');
    Route::post('deleteFavorite', 'api\UserController@deleteFavorite');
    Route::get('favorites', 'api\UserController@starredVendors');

    // user
    Route::get('user/{id}', 'api\UserController@userDetail');
    Route::get('userProfile', 'api\UserController@getUserProfile');
    Route::post('updateProfile', 'api\UserController@updateProfile');
    Route::post('changePassword', 'api\UserController@changePassword');

    //chat
    Route::get('rooms', 'api\ChatController@index');
    Route::get('chats/{room_id}', 'api\ChatController@chats');
    Route::post('addChat', 'api\ChatController@addChatMessage');
    Route::get('checkOrCreateAdminRoom', 'api\ChatController@checkOrCreateAdminRoom');

    //Notifications
    Route::get('notifications', 'api\NotificationController@index');

    Route::get('logout', 'api\AuthController@logout');
    
    //iButton
    Route::get('iButton', 'api\UserController@ibutton');

});
