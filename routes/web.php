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

Route::get('/mediakey', 'MediakeyController@index')->name('mediakey.index');
Route::get('/mediakey/result', 'MediakeyController@show');
Route::post('/mediakey', 'MediakeyController@finder');
Route::post('/mediakey/import', 'MediakeyController@import')->name('importMediakey');

Route::get('/cellers', 'CellersController@index')->name('cellers.index');
Route::post('/cellers', 'CellersController@finder');
Route::post('/cellers/import', 'CellersController@import')->name('importCellers');

Route::get('/aliado', 'AliadoController@index')->name('aliado.index');
Route::post('/aliado', 'AliadoController@finder');
Route::post('/aliado/import', 'AliadoController@import')->name('importAliado');

Route::get('/asmas', 'AsmasController@index')->name('asmas.index');
Route::post('/asmas', 'AsmasController@finder');
Route::post('/asmas/import', 'AsmasController@import')->name('importAsmas');

Route::get('export', 'MyController@export')->name('export');
Route::get('importExportView', 'MyController@importExportView');
Route::post('import', 'MyController@import')->name('import');

Route::get('/pages  ','PagesController@index')->name('pages.index');
Route::get('/about','PagesController@about')->name('pages.about');
