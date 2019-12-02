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
Route::get('/mediakey/last', 'MediakeyController@last')->name('mediakey.last');
Route::post('/mediakey/banorte', 'MediakeyController@banorte')->name('mediakey.banorte');
Route::post('/mediakey/banorte-pdf', 'MediakeyController@banortePdf')->name('mediakey.banortePdf');


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
Route::get('/cellers/last', 'CellersController@last')->name('cellers.last');
/*
|--------------------------------------------------------------------------
| Aliado Routes
|--------------------------------------------------------------------------
|
|
*/
Route::get('/aliado', 'AliadoController@index')->name('aliado.index');
Route::get('/aliado/last', 'AliadoController@last')->name('aliado.last');
Route::post('/aliado', 'AliadoController@store')->name('aliado.store');
Route::post('/aliado/import', 'AliadoController@import')->name('importAliado');
Route::post('/aliado/accepted', 'AliadoController@accepted')->name('aliado.accepted');
Route::post('/aliado/banorte', 'AliadoController@banorte')->name('aliado.banorte');
Route::post('/aliado/banorte-pdf', 'AliadoController@banortePdf')->name('aliado.banortePdf');

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
| Sanborns Routes
|--------------------------------------------------------------------------
|
|storeChargesReturns
*/
Route::get('/sanborns', 'SanbornsController@index')->name('sanborns.index');
Route::post('/sanborns/store', 'SanbornsController@store');
Route::get('/sanbornscobro', 'SanbornsCobrosController@index')->name('sanbornscobro.index');
Route::post('/sanbornscobro/storechargesreturns', 'SanbornsCobrosController@storeChargesReturns')->name('sanbornsStoreChargesReturnsImport');
Route::post('/sanbornscobro/search', 'SanbornsCobrosController@search')->name('sanbornsSearch');
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

Route::get('/file_cobro', 'BanorteController@index');

