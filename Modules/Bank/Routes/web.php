<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth']], function () {
    Route::get('bank', 'BankController@index')->name('bank');
    Route::get('manage-account', 'ManageBankController@allBank')->name('manage-account');
    Route::group(['prefix' => 'bank', 'as'=>'bank.'], function () {
        Route::get('manage-bank-create', 'ManageBankController@create')->name('create.manage');
        Route::post('manage-store-or-update', 'ManageBankController@store_or_update_data')->name('manage.store.or.update');
        Route::post('manage-bank-datatable-data', 'ManageBankController@get_datatable_data')->name('manage.bank.datatable.data');
        Route::post('manage.edit', 'ManageBankController@manageEdit')->name('manage.edit');

        Route::post('get-employee', 'BankController@get_employee')->name('get.employee');
        Route::get('create', 'BankController@create')->name('create');
        Route::post('datatable-data', 'BankController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'BankController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'BankController@edit')->name('edit');
        Route::post('change-status', 'BankController@change_status')->name('change.status');
        Route::post('delete', 'BankController@delete')->name('delete');
    });

    Route::get('bank-transaction', 'BankTransactionController@index')->name('bank.transaction');
    Route::post('store-bank-transaction', 'BankTransactionController@store')->name('store.bank.transaction');

    Route::get('bank-ledger', 'BankController@bank_ledger')->name('bank.ledger');
    Route::post('bank-ledger-data', 'BankController@bank_ledger_data')->name('bank.ledger.data');
});
