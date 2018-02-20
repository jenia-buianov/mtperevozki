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
Route::get('/confirm/{token}', 'Auth\LoginController@confirm')->name('confirm');
Route::get('/{lang}/subscribes/dismiss/{id}', 'SubscribesController@dismiss')->name('subscribes.dismiss');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/{lang}/subscribes', 'SubscribesController@index')->name('subscribes');
    Route::get('/{lang}/subscribes/enable/{id}', 'SubscribesController@enable')->name('subscribes.enable');
    Route::get('/{lang}/settings', 'HomeController@settings')->name('settings');
    Route::post('/{lang}/settings', 'HomeController@settingsSave')->name('settings.save');
    Route::post('/{lang}/settings/password', 'HomeController@settingsPassword')->name('settings.password');
});
Route::post('/{lang}/sendForm', 'FormController@addCargo')->name('form.add_cargo');
Route::post('/{lang}/sendPostCargoForm', 'FormController@addPostCargo')->name('form.add_post_cargo');
Route::post('/{lang}/sendPassengersCargoForm', 'FormController@addPassengersCargo')->name('form.add_passengers_cargo');
Route::post('/{lang}/sendTransportForm', 'FormController@addTransport')->name('form.add_transport');
Route::post('/{lang}/sendPostTransportForm', 'FormController@addPostTransport')->name('add_post_transport');
Route::post('/{lang}/sendPassengersTransportForm', 'FormController@addPassengersTransport')->name('add_passengers_transport');
Route::post('/{lang}/city', 'FormController@setCity');
Route::get('/{lang}', 'HomeController@index');
Route::get('/{lang}/{page}','HomeController@index');
