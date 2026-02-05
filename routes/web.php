<?php

use App\Http\Controllers\SslCommerzPaymentController;
use Illuminate\Support\Facades\Artisan;
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

Auth::routes(['register' => false]);


Route::group(['middleware' => ['auth']], function () {

    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('/weekly', 'HomeController@weeklyReport')->name('dashboard.weekly');
    Route::get('/monthly', 'HomeController@monthlyReport')->name('dashboard.monthly');
    Route::get('/daily', 'HomeController@dailyReport')->name('dashboard.daily');

    Route::get('dashboard-data/{start_date}/{end_date}', 'HomeController@dashboard_data');
    Route::get('unauthorized', 'HomeController@unauthorized')->name('unauthorized');
    Route::get('my-profile', 'MyProfileController@index')->name('my.profile');
    Route::post('update-profile', 'MyProfileController@updateProfile')->name('update.profile');
    Route::post('update-password', 'MyProfileController@updatePassword')->name('update.password');
    // Route::get('stock-notification', 'HomeController@stock_alert');

    //Menu Routes
    Route::get('menu', 'MenuController@index')->name('menu');
    Route::group(['prefix' => 'menu', 'as' => 'menu.'], function () {
        Route::post('datatable-data', 'MenuController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'MenuController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'MenuController@edit')->name('edit');
        Route::post('delete', 'MenuController@delete')->name('delete');
        Route::post('bulk-delete', 'MenuController@bulk_delete')->name('bulk.delete');


        //Module Routes
        Route::post('module-order/{menu}', 'ModuleController@orderItem')->name('module.order');
        Route::get('builder/{menu}', 'ModuleController@index')->name('builder');
        Route::post('items', 'ModuleController@get_menu_modules')->name('items');
        Route::group(['prefix' => 'module', 'as' => 'module.'], function () {
            Route::get('create/{menu}', 'ModuleController@create')->name('create');
            Route::post('store-or-update', 'ModuleController@storeOrUpdate')->name('store.or.update');
            Route::post('edit', 'ModuleController@edit')->name('edit');
            Route::post('delete', 'ModuleController@delete')->name('delete');

            //Permission Routes
            Route::get('permission', 'PermissionController@index')->name('permission');
            Route::group(['prefix' => 'menu', 'as' => 'permission.'], function () {
                Route::post('datatable-data', 'PermissionController@get_datatable_data')->name('datatable.data');
                Route::post('store', 'PermissionController@store')->name('store');
                Route::post('edit', 'PermissionController@edit')->name('edit');
                Route::post('update', 'PermissionController@update')->name('update');
                Route::post('delete', 'PermissionController@delete')->name('delete');
                Route::post('bulk-delete', 'PermissionController@bulk_delete')->name('bulk.delete');
            });
        });
    });

    //Visa Type
    Route::get('visa-type', 'VisaTypeController@index')->name('visaType');
    Route::group(['prefix' => 'visaType', 'as' => 'visaType.'], function () {
        Route::post('datatable-data', 'VisaTypeController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'VisaTypeController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'VisaTypeController@edit')->name('edit');
        Route::post('delete', 'VisaTypeController@delete')->name('delete');
        Route::post('bulk-delete', 'VisaTypeController@bulk_delete')->name('bulk.delete');
    });

    //Country
    Route::get('country-list', 'CountryController@index')->name('country');
    Route::group(['prefix' => 'country', 'as' => 'country.'], function () {
        Route::post('datatable-data', 'CountryController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'CountryController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'CountryController@edit')->name('edit');
        Route::post('delete', 'CountryController@delete')->name('delete');
        Route::post('bulk-delete', 'CountryController@bulk_delete')->name('bulk.delete');
    });

    //Department Routes
    Route::get('department', 'DepartmentController@index')->name('department');
    Route::group(['prefix' => 'department', 'as' => 'department.'], function () {
        Route::post('datatable-data', 'DepartmentController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'DepartmentController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'DepartmentController@edit')->name('edit');
        Route::post('delete', 'DepartmentController@delete')->name('delete');
        Route::post('bulk-delete', 'DepartmentController@bulk_delete')->name('bulk.delete');
    });

    //Role Routes
    Route::get('role', 'RoleController@index')->name('role');
    Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
        Route::get('create', 'RoleController@create')->name('create');
        Route::post('datatable-data', 'RoleController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RoleController@store_or_update_data')->name('store.or.update');
        Route::get('edit/{id}', 'RoleController@edit')->name('edit');
        Route::get('view/{id}', 'RoleController@show')->name('view');
        Route::post('delete', 'RoleController@delete')->name('delete');
        Route::post('bulk-delete', 'RoleController@bulk_delete')->name('bulk.delete');
    });

    //User Routes
    Route::get('user', 'UserController@index')->name('user');
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::post('datatable-data', 'UserController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'UserController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'UserController@edit')->name('edit');
        Route::post('view', 'UserController@show')->name('view');
        Route::post('delete', 'UserController@delete')->name('delete');
        Route::post('bulk-delete', 'UserController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'UserController@change_status')->name('change.status');
    });

    Route::get('department-wise-role/{id}', 'UserController@departmentWiseRole')->name('department.wise.role');
    Route::get('department-wise-user/{id}', 'UserController@departmentWiseUser')->name('department.wise.user');
    //Software Settings Route
    Route::get('setting', 'SettingController@index')->name('software.setting');
    Route::post('general-setting', 'SettingController@general_setting')->name('general.setting');
    Route::post('mail-setting', 'SettingController@mail_setting')->name('mail.setting');

    //All Report
    Route::get('totalcollection-report', 'ReportController@totalCollection')->name('collection');
    Route::get('totalcollection-report-data', 'ReportController@totalCollectionData')->name('collection.report');

    Route::get('total-collection-report-details', 'ReportController@totalCollectionDetails')->name('collection.details');
    Route::get('totalcollection-report-details', 'ReportController@totalCollectionDetailData')->name('collection.report.details');


    Route::get('totaldue-report', 'ReportController@totalDue')->name('totalDue');
    Route::get('totaldue-report-data', 'ReportController@totalDueData')->name('due.report');

    Route::get('totalfilereceived-report', 'ReportController@received')->name('received');
    Route::get('received-report-data', 'ReportController@receivedData')->name('received.report');

    Route::get('totalfileprocessing-report', 'ReportController@processing')->name('processing');
    Route::get('processing-report-data', 'ReportController@processingData')->name('processing.report');

    Route::get('missing-document-report', 'ReportController@missingDoc')->name('missing.document');
    Route::get('missing-document-report-data', 'ReportController@missingDocData')->name('missing.document.report');

    Route::get('submitted-to-embassy-report', 'ReportController@submittedEmbassy')->name('submittedEmbassy');
    Route::get('submitted-to-embassy-report-data', 'ReportController@submittedEmbassyData')->name('submittedEmbassy.report');


    Route::get('ready-for-delivery-report', 'ReportController@delivery')->name('delivery');
    Route::get('ready-for-delivery-report-data', 'ReportController@deliveryData')->name('delivery.report');

    Route::get('delivered-report', 'ReportController@delivered')->name('delivered');
    Route::get('delivered-report-data', 'ReportController@deliveredData')->name('delivered.report');


    Route::get('totalfileprocess-report', 'ReportController@fileProcess')->name('fileProcess');
    Route::get('fileprocess-report-data', 'ReportController@fileProcessData')->name('fileProcess.report');

    Route::get('/foo', function () {
        Artisan::call('storage:link');
    });

    // SSLCOMMERZ Start
    Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);


    //SSLCOMMERZ END


});
Route::get('/get-payment-date/{id}', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);
Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
Route::get('/test-ssl', [SslCommerzPaymentController::class, 'testSslCommerz']);

Route::get('payment-and-checklist', 'ReportController@paymentAndChecklist')->name('payment.and.checklist');

Route::get('app-payment-update', 'ReportController@appPaymentUpdate')->name('app.payment.update');
Route::get('service-charge-report', 'ReportController@serviceChargeReport')->name('service.charge.report');
Route::get('service-charge-report-data', 'ReportController@serviceChargeReportData')->name('service.charge.report.data');
