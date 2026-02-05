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

Route::prefix('crm')->group(function() {
    Route::get('/', 'CrmController@index');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('corporates-customer', 'AssignCollectorController@index')->name('list');

    Route::group(['prefix' => 'onlineApp', 'as'=>'onlineApp.'], function () {
        Route::post('datatable-data', 'AssignCollectorController@get_datatable_data')->name('datatable.data');
        Route::get('edit/{id}', 'AssignCollectorController@edit')->name('edit');
        Route::post('update', 'AssignCollectorController@update_data')->name('update');
        Route::post('assign', 'AssignCollectorController@assign')->name('assign');
        Route::post('assignCollector', 'AssignCollectorController@assignCollector')->name('assignCollector');
        Route::post('status', 'AssignCollectorController@statusChange')->name('change.status');
    });

});
