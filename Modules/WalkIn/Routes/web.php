<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::get('walk-application-add', 'WalkInController@index')->name('create');
    Route::get('walk-applications', 'WalkInController@walkInApps')->name('walk.index');
    Route::get('all-application', 'WalkInController@allApps')->name('index');
    Route::get('online-application', 'WalkInController@onlineApps')->name('online.index');
    Route::group(['prefix' => 'walkIn', 'as' => 'walkIn.'], function () {
        Route::post('datatable-data', 'WalkInController@get_datatable_data')->name('datatable.data');
        Route::post('datatable-walkin-data', 'WalkInController@get_walkin_datatable_data')->name('datatable.walkIn.data');
        Route::post('datatable-online-data', 'WalkInController@get_online_datatable_data')->name('datatable.online.data');

        Route::get('create', 'WalkInController@create')->name('create');
        Route::get('edit/{id}', 'WalkInController@edit')->name('edit');
        Route::get('ready-for-delivery/{id}', 'WalkInController@readyForDelivery')->name('readyForDelivery');
        Route::post('ready-for-delivered', 'WalkInController@readyForDelivered')->name('readyForDelivered');
        Route::get('view/{id}', 'WalkInController@show');
        Route::post('delete', 'WalkInController@delete')->name('delete');
        Route::post('bulk-delete', 'WalkInController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'WalkInController@change_status')->name('change.status');
        Route::get('generate-ticket-code', 'WalkInController@generateTicketCode')->name('generate.ticket.code');
        Route::post('store', 'WalkInController@store')->name('store');
        Route::post('store-or-update', 'WalkInController@store_or_update_data')->name('store.or.update');
        Route::post('hold-or-update', 'WalkInController@hold_or_update_data')->name('hold.or.update');
        Route::post('update', 'WalkInController@update_data')->name('update');
        Route::post('update_hold', 'WalkInController@update_Hold_data')->name('update_hold');
        Route::post('status', 'WalkInController@statusChange')->name('change.status');
        Route::post('status/payment', 'WalkInController@paymentStatusChange')->name('payment.status');
        Route::get('payment/{id}', 'WalkInController@payment')->name('payment');

        Route::get('barcode/{id}', 'WalkInController@barcode')->name('barcode');
        Route::get('barcodeData/{id}', 'WalkInController@barcodeData')->name('barcode.data');
        Route::get('barcodeShow/{id}', 'WalkInController@barcodeShoe')->name('barcode.show');
        Route::get('moneyReceipt/{id}', 'WalkInController@moneyReceipt')->name('money.receipt');
        Route::get('po-invoice/{id}', 'WalkInController@poInvoice')->name('po.invoice');
        Route::post('save-po-invoice', 'WalkInController@poInvoiceSave')->name('po.invoice.save');
        Route::post('get-max-discount', 'WalkInController@getMaxDiscount')->name('get.max.discount');

    });
    Route::get('type-wise-cat/{id}', 'WalkInController@VisaTypeWiseCat')->name('type.wise.cat');
    Route::get('cat-wise-checklist/{id}', 'WalkInController@catWiseChecklist')->name('cat.wise.checklist');
    Route::get('cat-wise-checklist-child/{id}', 'WalkInController@catWiseChecklistChild')->name('cat.wise.checklist.child');
    Route::get('application-type-wise-customer/{id}', 'WalkInController@agentCorporeteCustomer')->name('type.wise.customer');

    Route::get('get-accounts/{id}', 'WalkInController@getAccount')->name('get.accounts');
});
