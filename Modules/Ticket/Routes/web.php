<?php


Route::group(['middleware' => ['auth']], function (){
    Route::get('ticket', 'TicketController@index')->name('ticket');
    Route::group(['prefix' => 'ticket', 'as'=>'ticket.'], function (){
        Route::post('datatable-data', 'TicketController@get_datatable_data')->name('datatable.data');
        Route::post('edit', 'TicketController@edit')->name('edit');
        Route::post('edit/child', 'TicketController@editChild')->name('edit.child');
        Route::post('update/child', 'TicketController@updateChild')->name('update.child');
        Route::post('view', 'TicketController@show')->name('view');
        Route::post('delete', 'TicketController@delete')->name('delete');
        Route::post('bulk-delete', 'TicketController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'TicketController@change_status')->name('change.status');
        Route::get('generate-ticket-code', 'TicketController@generateTicketCode')->name('generate.ticket.code');
        Route::post('store-or-update', 'TicketController@store_or_update_data')->name('store.or.update');

        Route::post('assign-employee', 'TicketController@assignEmployss')->name('assign.employee');

        Route::put('/ticket/{id}/assign','TicketController@approval')->name('assign.executive');
        Route::put('/ticket/{id}/accept','TicketController@approvalAccept')->name('executive.accept');
        Route::post('/ticket/{id}/accepted','TicketController@approvalAccepted')->name('executive.accepted');

        Route::get('/show/{id}', 'TicketController@show')->name('ticket.show');

    });
});
