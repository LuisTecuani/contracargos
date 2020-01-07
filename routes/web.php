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
Route::post('/cellers/banorte-pdf', 'CellersController@banortePdf')->name('cellers.banortePdf');


Route::post('/cellers/banorte', 'CellersController@banorte')->name('cellers.banorte');
Route::get('/cellers/banorte', 'CellersBanorteController@index')->name('cellers.banorte');
Route::post('/cellers/banorte/ftp', 'CellersBanorteController@Ftp')->name('cellers.cobroBanorteFtp');
Route::post('/cellers/banorte/billingRejected', 'CellersBanorteController@billingRejected')->name('cellers.banorteBillingRejected');
Route::post('/cellers/banorte/usersTextbox', 'CellersBanorteController@usersTextbox')->name('cellers.banorteUsersTextbox');
Route::get('/cellers/banorte/ftpProsa', 'CellersBanorteController@ftpProsa')->name('cellers.banorteFtpProsa');
Route::get('/cellers/banorte/csvBanorte', 'CellersBanorteController@csvBanorte')->name('cellers.banorteCsvBanorte');
Route::get('/cellers/banorte/csvBilling', 'Exports\CellersBanorteController@export')->name('cellers.banorteCsvBilling');
/*
|--------------------------------------------------------------------------
| Aliado Routes
|--------------------------------------------------------------------------
|
|
*/
Route::get('/aliado', 'AliadoController@index')->name('aliado.index');
Route::get('/aliado/chargeback', 'AliadoChargebackController@index')->name('aliado.chargeback.index');
Route::post('/aliado/chargeback/store', 'AliadoChargebackController@store')->name('aliadoChargeback.store');
Route::get('/aliado/chargeback/show', 'AliadoChargebackController@show')->name('aliadoChargeback.show');
Route::post('/aliado/chargeback/storeTxt', 'AliadoChargebackController@storeTxt')->name('aliadoChargeback.storeTxt');


Route::get('/aliado/blacklist', 'AliadoBlacklistController@index')->name('aliado.blacklist.index');
Route::post('/aliado/blacklist/store', 'AliadoBlacklistController@store')->name('aliadoBlacklist.store');

Route::get('/aliado/responses', 'AliadoResponsesController@index')->name('aliado.responses.index');
Route::post('/aliado/responses/storeReps', 'AliadoResponsesController@storeReps')->name('aliado.responses.storeReps');
Route::post('/aliado/responses/storePdf', 'AliadoResponsesController@storePdf')->name('aliado.responses.storePdf');


Route::post('/aliado', 'AliadoController@store')->name('aliado.store');
Route::post('/aliado/accepted', 'AliadoController@accepted')->name('aliado.accepted');

Route::get('/aliado/file_making', 'AliadoFileMakingController@index')->name('aliado.file_making.index');

Route::get('/aliado/billing_users', 'AliadoBillingUsersController@index')->name('aliado.billing_users.index');
Route::post('/aliado/billing_users/storeFtp', 'AliadoBillingUsersController@storeFtp')->name('aliado.billing_users.storeFtp');
Route::post('/aliado/billing_users/storeRejected', 'AliadoBillingUsersController@storeRejected')->name('aliado.billing_users.storeRejected');
Route::post('/aliado/billing_users/storeTextbox', 'AliadoBillingUsersController@storeTextbox')->name('aliado.billing_users.storeTextbox');


Route::post('/aliado/banorte', 'AliadoController@banorte')->name('aliado.banorte');
Route::get('/aliado/banorte', 'AliadoBanorteController@index')->name('aliado.banorte');
Route::post('/aliado/banorte/ftp', 'AliadoBanorteController@Ftp')->name('aliado.cobroBanorteFtp');
Route::post('/aliado/banorte/billingRejected', 'AliadoBanorteController@billingRejected')->name('aliado.banorteBillingRejected');
Route::post('/aliado/banorte/usersTextbox', 'AliadoBanorteController@usersTextbox')->name('aliado.banorteUsersTextbox');
Route::get('/aliado/banorte/ftpProsa', 'AliadoBanorteController@ftpProsa')->name('aliado.banorteFtpProsa');
Route::get('/aliado/banorte/csvBanorte', 'AliadoBanorteController@csvBanorte')->name('aliado.banorteCsvBanorte');
Route::get('/aliado/banorte/csvBilling', 'Exports\AliadoBanorteController@export')->name('aliado.banorteCsvBilling');
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
Route::post('sanbornscobro/searchdetails', 'SanbornsCobrosController@searchDetails')->name('searchDetails');

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

