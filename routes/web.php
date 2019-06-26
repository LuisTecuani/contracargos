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
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home');

/*
|--------------------------------------------------------------------------
| Mediakey Routes
|--------------------------------------------------------------------------
|
|
*/
Route::get('/mediakey', 'MediakeyController@index')->name('mediakey.index');
Route::post('/mediakey', 'MediakeyController@store')->name('mediakey.store');
Route::post('/mediakey/import', 'MediakeyController@import')->name('importMediakey');
Route::post('/mediakey/store2', 'MediakeyController@store2')->name('mediakey.store2');
/*
|--------------------------------------------------------------------------
| Cellers Routes
|--------------------------------------------------------------------------
|
|
*/
Route::get('/cellers', 'CellersController@index')->name('cellers.index');
Route::post('/cellers', 'CellersController@store')->name('cellers.store');
Route::post('/cellers/import', 'CellersController@import')->name('importCellers');
Route::post('/cellers/store2', 'CellersController@store2')->name('cellers.store2');
/*
|--------------------------------------------------------------------------
| Aliado Routes
|--------------------------------------------------------------------------
|
|
*/
Route::get('/aliado', 'AliadoController@index')->name('aliado.index');
Route::post('/aliado', 'AliadoController@store')->name('aliado.store');
Route::post('/aliado/import', 'AliadoController@import')->name('importAliado');
Route::post('/aliado/store2', 'AliadoController@store2')->name('aliado.store2');
/*
|--------------------------------------------------------------------------
| Asmas Routes
|--------------------------------------------------------------------------
|
|
*/
Route::get('/asmas', 'AsmasController@index')->name('asmas.index');
Route::post('/asmas', 'AsmasController@store')->name('asmas.store');
Route::post('/asmas/import', 'AsmasController@import')->name('importAsmas');
Route::post('/asmas/store2', 'AsmasController@store2')->name('asmas.store2');
/*
|--------------------------------------------------------------------------
| Pendiente
|--------------------------------------------------------------------------
|
|
*/
Route::get('export', 'MyController@export')->name('export');
Route::get('importExportView', 'MyController@importExportView');
Route::post('import', 'MyController@import')->name('import');

