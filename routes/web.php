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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/customers', 'HomeController@customersPerMonth')->name('customersPerMonth');

Route::get('/sales', 'HomeController@salesPerMonth')->name('salesPerMonth');

Route::post('/export', 'HomeController@export')->name('export');

Route::post('/stats/orders', 'HomeController@orders')->name('orders');

Route::post('/stats/customers', 'HomeController@customers')->name('customers');

Route::post('/stats/customersByMonth', 'HomeController@customersByMonth')->name('customersByMonth');

Route::post('/stats/salesByMonth', 'HomeController@salesByMonth')->name('salesByMonth');
