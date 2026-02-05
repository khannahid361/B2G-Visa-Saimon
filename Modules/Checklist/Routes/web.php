<?php

Route::group(['middleware' => ['auth']], function () {
    Route::get('checklist', 'ChecklistController@index')->name('checklist');
    Route::group(['prefix' => 'checklist', 'as'=>'checklist.'], function () {
        Route::post('datatable-data', 'ChecklistController@get_datatable_data')->name('datatable.data');
        Route::get('create', 'ChecklistController@create')->name('create');
        Route::get('edit/{id}', 'ChecklistController@edit')->name('edit');
        Route::get('duplicate/{id}', 'ChecklistController@duplicate')->name('duplicate');
        Route::get('view/{id}', 'ChecklistController@show');
        Route::post('delete', 'ChecklistController@delete')->name('delete');
        Route::post('update/{id}', 'ChecklistController@update')->name('update');
        Route::post('checklist-update', 'ChecklistController@checklistUpdate')->name('list.update');
        Route::post('checklist-store', 'ChecklistController@checklistStore')->name('list.store');
        Route::post('bulk-delete', 'ChecklistController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ChecklistController@change_status')->name('change.status');
        Route::get('generate-ticket-code', 'ChecklistController@generateTicketCode')->name('generate.ticket.code');
        Route::post('store', 'ChecklistController@store')->name('store');
        Route::post('store-or-update', 'ChecklistController@store_or_update_data')->name('store.or.update');
    });
});
