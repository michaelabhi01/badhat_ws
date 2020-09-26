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
 Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});
 
Route::get('/', function () {
    // echo "Home";
    return view('home');
});

Route::get('/product/{id}', function () {
    return redirect("https://play.google.com/store/apps/details?id=com.badhat.app");
});
Route::get('/user/{id}', function () {
    return redirect("https://play.google.com/store/apps/details?id=com.badhat.app");
});
// Route::get('/', function () {
//     return redirect('/admin');
// });

Route::group(['prefix' => 'admin/'], function () {

    Route::match(['get','post'],'login', 'admin\AuthController@login')->name('login');

    // Route::group(['middleware' => ['admin']], function () {
    Route::group(['middleware' => ['auth']], function () {

        // auth
        Route::get('logout', 'admin\AuthController@logout')->name('logout');

        Route::get('dashboard', 'admin\UserController@dashboard')->name('dashboard');
        Route::get('users', 'admin\UserController@index')->name('users');
        Route::get('products', 'admin\ProductController@index')->name('products');
        Route::get('orders', 'admin\OrderController@index')->name('orders');

        Route::get('categories', 'admin\CategoryController@index')->name('categories');
        Route::get('subcategories/{category_id}', 'admin\CategoryController@subcategory')->name('subcategories');
        Route::get('verticals/{subcategory_id}', 'admin\CategoryController@vertical')->name('verticals');

        Route::post('event/{event_id}/{event_type}', 'admin\CategoryController@eventAdd')->name('event.add');
        Route::post('event/edit/{event_id}/{event_type}', 'admin\CategoryController@eventEdit')->name('event.edit');
        Route::get('event/delete/{event_id}/{event_type}', 'admin\CategoryController@eventDelete')->name('event.delete');

    });

});


define('DATE_FORMAT', 'd F, Y');
