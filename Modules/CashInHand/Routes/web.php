<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::get('manage-cash-in-hand', 'CashInHandController@index')->name('manage-cash-in-hand');

    Route::group(['prefix' => 'cashInHand', 'as'=>'cashInHand.'], function (){
        Route::get('manage-cashInHand-create', 'CashInHandController@create')->name('create.manage');
        Route::post('manage-store-or-update', 'CashInHandController@store_or_update_data')->name('manage.store.or.update');
        Route::post('manage-bank-datatable-data', 'CashInHandController@get_datatable_data')->name('manage.bank.datatable.data');
        Route::post('manage.edit', 'CashInHandController@manageEdit')->name('manage.edit');

        Route::post('get-employee', 'CashInHandController@get_employee')->name('get.employee');
        Route::get('create', 'CashInHandController@create')->name('create');
        Route::post('datatable-data', 'CashInHandController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'CashInHandController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'CashInHandController@edit')->name('edit');
        Route::post('change-status', 'CashInHandController@change_status')->name('change.status');
        Route::post('delete', 'CashInHandController@delete')->name('delete');
    });

});
