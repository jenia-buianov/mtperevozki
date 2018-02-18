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

Auth::routes();
Route::get('logout','Auth\LoginController@logout');
Route::get('/', 'HomeController@index');
Route::get('/confirm/{token}', 'Auth\LoginController@confirm');
Route::post('/{lang}/sendForm', 'FormController@addCargo');
Route::post('/{lang}/sendPostCargoForm', 'FormController@addPostCargo');
Route::post('/{lang}/sendPassengersCargoForm', 'FormController@addPassengersCargo');
Route::post('/{lang}/sendTransportForm', 'FormController@addTransport');
Route::post('/{lang}/sendPostTransportForm', 'FormController@addPostTransport');
Route::post('/{lang}/sendPassengersTransportForm', 'FormController@addPassengersTransport');
Route::post('/{lang}/city', 'FormController@setCity');
Route::get('/{lang}', 'HomeController@index');
Route::get('/{lang}/{page}','HomeController@index');
