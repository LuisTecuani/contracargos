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

Route::get('/mediakey', 'mediakey@index')->name('mediakey.index');
Route::get('/mediakey/result', 'mediakey@show');
Route::post('/mediakey', 'mediakey@finder');

Route::get('/cellers', 'CellersController@index')->name('cellers.index');
Route::post('/cellers', 'CellersController@finder');

Route::get('/aliado', 'AliadoController@index')->name('aliado.index');
Route::post('/aliado', 'AliadoController@finder');

Route::get('/asmas', 'AsmasController@index')->name('asmas.index');
Route::post('/asmas', 'AsmasController@finder');

Route::get('export', 'MyController@export')->name('export');
Route::get('importExportView', 'MyController@importExportView');
Route::post('import', 'MyController@import')->name('import');

Route::get('/pages  ','PagesController@index')->name('pages.index');
Route::get('/about','PagesController@about')->name('pages.about');
