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
Route::post('/mediakey/billing_users/storeRejectedProsa', 'MediakeyBillingUsersController@storeRejectedProsa')->name('mediakey.billing_users.storeRejectedProsa');
Route::post('/mediakey/billing_users/storeRejectedBanorte', 'MediakeyBillingUsersController@storeRejectedBanorte')->name('mediakey.billing_users.storeRejectedBanorte');
Route::post('/mediakey/billing_users/storeTextbox', 'MediakeyBillingUsersController@storeTextbox')->name('mediakey.billing_users.storeTextbox');

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

Route::get('/cellers/blacklist', 'CellersBlacklistController@index')->name('cellers.blacklist.index');
Route::post('/cellers/blacklist/store', 'CellersBlacklistController@store')->name('cellersBlacklist.store');
Route::post('/cellers/blacklist/storeChargedback', 'CellersBlacklistController@storeChargedback')->name('cellersBlacklist.storeChargedback');

Route::get('/cellers/responses', 'CellersResponsesController@index')->name('cellers.responses.index');
Route::post('/cellers/responses/storeReps', 'CellersResponsesController@storeReps')->name('cellers.responses.storeReps');
Route::post('/cellers/responses/storePdf', 'CellersResponsesController@storePdf')->name('cellers.responses.storePdf');

Route::get('/cellers/file_making', 'CellersFileMakingController@index')->name('cellers.file_making.index');
Route::get('/cellers/file_making/exportBanorte', 'CellersFileMakingController@exportBanorte')->name('cellers.file_making.exportBanorte');

Route::get('/cellers/billing_users', 'CellersBillingUsersController@index')->name('cellers.billing_users.index');
Route::post('/cellers/billing_users/storeFtp', 'CellersBillingUsersController@storeFtp')->name('cellers.billing_users.storeFtp');
Route::post('/cellers/billing_users/storeRejectedProsa', 'CellersBillingUsersController@storeRejectedProsa')->name('cellers.billing_users.storeRejectedProsa');
Route::post('/cellers/billing_users/storeToBanorte', 'CellersBillingUsersController@storeToBanorte')->name('cellers.billing_users.storeToBanorte');
Route::post('/cellers/billing_users/storeTextbox', 'CellersBillingUsersController@storeTextbox')->name('cellers.billing_users.storeTextbox');


Route::get('/cellers/blacklist', 'CellersBlacklistController@index')->name('cellers.blacklist.index');

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
Route::post('/aliado/chargeback/storeImage', 'AliadoChargebackController@storeImage')->name('aliadoChargeback.storeImage');
Route::get('/aliado/chargeback/show', 'AliadoChargebackController@show')->name('aliadoChargeback.show');

Route::post('/aliado/banorte/chargeback/store', 'AliadoBanorteChargebackController@store')->name('aliadoBanorteChargeback.store');

Route::get('/aliado/blacklist', 'AliadoBlacklistController@index')->name('aliado.blacklist.index');
Route::post('/aliado/blacklist/store', 'AliadoBlacklistController@store')->name('aliadoBlacklist.store');
Route::post('/aliado/blacklist/storeChargedback', 'AliadoBlacklistController@storeChargedback')->name('aliadoBlacklist.storeChargedback');

Route::get('/aliado/responses', 'AliadoResponsesController@index')->name('aliado.responses.index');
Route::post('/aliado/responses/storeReps', 'AliadoResponsesController@storeReps')->name('aliado.responses.storeReps');
Route::post('/aliado/responses/storePdf', 'AliadoResponsesController@storePdf')->name('aliado.responses.storePdf');

Route::get('/aliado/file_making', 'AliadoFileMakingController@index')->name('aliado.file_making.index');
Route::get('/aliado/file_making/exportBanorte', 'AliadoFileMakingController@exportBanorte')->name('aliado.file_making.exportBanorte');
Route::get('/aliado/file_making/export0897', 'AliadoFileMakingController@export0897')->name('aliado.file_making.export0897');


Route::get('/aliado/billing_users', 'AliadoBillingUsersController@index')->name('aliado.billing_users.index');
Route::post('/aliado/billing_users/storeFtp', 'AliadoBillingUsersController@storeFtp')->name('aliado.billing_users.storeFtp');
Route::post('/aliado/billing_users/storeRejectedProsa', 'AliadoBillingUsersController@storeRejectedProsa')->name('aliado.billing_users.storeRejectedProsa');
Route::post('/aliado/billing_users/storeToBanorte', 'AliadoBillingUsersController@storeToBanorte')->name('aliado.billing_users.storeToBanorte');
Route::post('/aliado/billing_users/storeTo3918', 'AliadoBillingUsersController@storeTo3918')->name('aliado.billing_users.storeTo3918');
Route::post('/aliado/billing_users/storeTextbox', 'AliadoBillingUsersController@storeTextbox')->name('aliado.billing_users.storeTextbox');

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

/*
|--------------------------------------------------------------------------
| Shared Tools Routes
|--------------------------------------------------------------------------
|
|
*/

Route::get('/tools', 'ToolsController@index')->name('tools.index');

Route::get('/bins', 'BinsController@index')->name('bins.index');
Route::post('/bins', 'BinsController@store')->name('bins.store');
Route::get('/bins/show', 'BinsController@show')->name('bins.show');

Route::get('/bins/historic', 'BinsHistoricController@index')->name('binsHistoric.index');
Route::post('/bins/historic/store', 'BinsHistoricController@store')->name('binsHistoric.store');
Route::post('/bins/historic/import', 'BinsHistoricController@import')->name('binsHistoric.import');

Route::get('/find_user', 'FindUserController@index')->name('find_user.index');
Route::post('/find_user/show', 'FindUserController@show')->name('find_user.show');
