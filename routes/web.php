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

Route::get('/mediakey/chargeback', 'MediakeyChargebackController@index')->name('mediakey.chargeback.index');
Route::post('/mediakey/chargeback/store', 'MediakeyChargebackController@store')->name('mediakeyChargeback.store');
Route::get('/mediakey/chargeback/show', 'MediakeyChargebackController@show')->name('mediakeyChargeback.show');
Route::post('/mediakey/chargeback/storeTxt', 'MediakeyChargebackController@storeTxt')->name('mediakeyChargeback.storeTxt');

Route::post('/mediakey/banorte/chargeback/store', 'MediakeyBanorteChargebackController@store')->name('mediakeyBanorteChargeback.store');

Route::get('/mediakey/blacklist', 'MediakeyBlacklistController@index')->name('mediakey.blacklist.index');
Route::post('/mediakey/blacklist/store', 'MediakeyBlacklistController@store')->name('mediakeyBlacklist.store');

Route::get('/mediakey/responses', 'MediakeyResponsesController@index')->name('mediakey.responses.index');
Route::post('/mediakey/responses/storeReps', 'MediakeyResponsesController@storeReps')->name('mediakey.responses.storeReps');
Route::post('/mediakey/responses/storePdf', 'MediakeyResponsesController@storePdf')->name('mediakey.responses.storePdf');

Route::get('/mediakey/file_making', 'MediakeyFileMakingController@index')->name('mediakey.file_making.index');

Route::get('/mediakey/billing_users', 'MediakeyBillingUsersController@index')->name('mediakey.billing_users.index');
Route::post('/mediakey/billing_users/storeFtp', 'MediakeyBillingUsersController@storeFtp')->name('mediakey.billing_users.storeFtp');
Route::post('/mediakey/billing_users/storeRejected', 'MediakeyBillingUsersController@storeRejected')->name('mediakey.billing_users.storeRejected');
Route::post('/mediakey/billing_users/storeTextbox', 'MediakeyBillingUsersController@storeTextbox')->name('mediakey.billing_users.storeTextbox');







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

Route::get('/cellers/chargeback', 'CellersChargebackController@index')->name('cellers.chargeback.index');
Route::post('/cellers/chargeback/store', 'CellersChargebackController@store')->name('cellersChargeback.store');
Route::get('/cellers/chargeback/show', 'CellersChargebackController@show')->name('cellersChargeback.show');

Route::post('/cellers/banorte/chargeback/store', 'CellersBanorteChargebackController@store')->name('cellersBanorteChargeback.store');

Route::get('/cellers/responses', 'CellersResponsesController@index')->name('cellers.responses.index');
Route::post('/cellers/responses/storeReps', 'CellersResponsesController@storeReps')->name('cellers.responses.storeReps');
Route::post('/cellers/responses/storePdf', 'CellersResponsesController@storePdf')->name('cellers.responses.storePdf');

Route::get('/cellers/file_making', 'CellersFileMakingController@index')->name('cellers.file_making.index');


Route::get('/cellers/billing_users', 'CellersBillingUsersController@index')->name('cellers.billing_users.index');
Route::post('/cellers/billing_users/storeFtp', 'CellersBillingUsersController@storeFtp')->name('cellers.billing_users.storeFtp');
Route::post('/cellers/billing_users/storeRejected', 'CellersBillingUsersController@storeRejected')->name('cellers.billing_users.storeRejected');
Route::post('/cellers/billing_users/storeTextbox', 'CellersBillingUsersController@storeTextbox')->name('cellers.billing_users.storeTextbox');



Route::post('/cellers', 'CellersController@store')->name('cellers.store');
Route::post('/cellers/import', 'CellersController@import')->name('importCellers');
Route::post('/cellers/store2', 'CellersController@store2')->name('cellers.store2');
Route::get('/cellers/last', 'CellersController@last')->name('cellers.last');
Route::post('/cellers/banorte-pdf', 'CellersController@banortePdf')->name('cellers.banortePdf');

Route::get('/cellers/blacklist', 'CellersBlacklistController@index')->name('cellers.blacklist.index');

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

Route::post('/aliado/banorte/chargeback/store', 'AliadoBanorteChargebackController@store')->name('aliadoBanorteChargeback.store');

Route::get('/aliado/blacklist', 'AliadoBlacklistController@index')->name('aliado.blacklist.index');
Route::post('/aliado/blacklist/store', 'AliadoBlacklistController@store')->name('aliadoBlacklist.store');

Route::get('/aliado/responses', 'AliadoResponsesController@index')->name('aliado.responses.index');
Route::post('/aliado/responses/storeReps', 'AliadoResponsesController@storeReps')->name('aliado.responses.storeReps');
Route::post('/aliado/responses/storePdf', 'AliadoResponsesController@storePdf')->name('aliado.responses.storePdf');

Route::get('/aliado/file_making', 'AliadoFileMakingController@index')->name('aliado.file_making.index');

Route::get('/aliado/billing_users', 'AliadoBillingUsersController@index')->name('aliado.billing_users.index');
Route::post('/aliado/billing_users/storeFtp', 'AliadoBillingUsersController@storeFtp')->name('aliado.billing_users.storeFtp');
Route::post('/aliado/billing_users/storeRejected', 'AliadoBillingUsersController@storeRejected')->name('aliado.billing_users.storeRejected');
Route::post('/aliado/billing_users/storeTextbox', 'AliadoBillingUsersController@storeTextbox')->name('aliado.billing_users.storeTextbox');

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
Route::post('sanbornscobro/searchdetails/', 'SanbornsCobrosController@searchDetails')->name('searchDetails');


