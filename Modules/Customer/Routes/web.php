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

//Route::prefix('customer')->group(function() {
//    Route::get('/', 'CustomerController@index');
//});

Route::group(['middleware' => ['auth']], function () {
    Route::get('customer', 'CustomerController@index')->name('customer');
    Route::get('customer-group', 'CustomerGroupController@groupIndex')->name('customer.group');
    Route::get('customer-group-assign', 'CustomerAssignGroupController@groupAssignIndex')->name('customer.group.assign');
    Route::get('inactive-customer', 'CustomerController@inactiveCustomer')->name('inactive.customer');
    Route::group(['prefix' => 'customer', 'as'=>'customer.'], function () {
        Route::get('customer-add', 'CustomerController@create')->name('add');
        Route::post('store', 'CustomerController@store')->name('store');
        Route::post('datatable-data', 'CustomerController@get_datatable_data')->name('datatable.data');
        Route::post('datatable-inactive_data', 'CustomerController@get_datatable_inactive_data')->name('datatable.inactive.data');
        Route::post('change-status', 'CustomerController@change_status')->name('change.status');
        Route::post('edit', 'CustomerController@edit')->name('edit');
        Route::post('view', 'CustomerController@show')->name('view');
        Route::post('delete', 'CustomerController@delete')->name('delete');
        Route::post('bulk-delete', 'CustomerController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'CustomerController@change_status')->name('change.status');

        // customer group
        Route::post('group-store', 'CustomerGroupController@geoupStore')->name('group.store');
        Route::post('group-datatable-data', 'CustomerGroupController@get_group_datatable_data')->name('group.datatable.data');
        Route::post('group-edit', 'CustomerGroupController@groupEdit')->name('group.edit');

        // customer group Assign
        Route::post('group-assign-store', 'CustomerAssignGroupController@geoupAssignStore')->name('group.assign.store');
        Route::post('group-assign-datatable-data', 'CustomerAssignGroupController@get_group_assign_datatable_data')->name('group.assign.datatable.data');
        Route::post('group-assign-edit', 'CustomerAssignGroupController@groupAssignEdit')->name('group.assign.edit');
    });
});
