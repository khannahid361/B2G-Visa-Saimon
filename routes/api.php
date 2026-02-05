<?php

use App\Http\Controllers\API\Customer\CustomerController;
use App\Http\Controllers\SslCommerzPaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Frontend\FrontendController;
use Modules\Customer\Entities\Customer;

Route::post('login', [CustomerController::class, 'authenticate']);
Route::post('register', [CustomerController::class, 'register']);
//forgot and reset password APIs
Route::post('forgot-password', [CustomerController::class, 'forgot'])->middleware('throttle:3,1');
Route::post('reset-password', [CustomerController::class, 'reset']);
//end forgot and reset password APIs

// Checklist search APIs
Route::get('fromCountries', [CustomerController::class, 'get_from_country']);
Route::get('toCountries', [CustomerController::class, 'get_to_country']);
Route::get('checklists', [CustomerController::class, 'get_checklist_data']);
Route::get('file-download/{id}', [CustomerController::class, 'fileDownload']);
Route::get('po-invoice-download/{id}', [CustomerController::class, 'poInvoiceDownload']);

//front end V1 route----------------------------
Route::get('v1/about-us', 'API\Customer\CustomerController@getAboutUs');
Route::get('v1/faq', 'API\Customer\CustomerController@getFaq');
Route::get('v1/service', 'API\Customer\CustomerController@getService');
Route::get('v1/slider', 'API\Customer\CustomerController@getSlider');
Route::get('v1/terms-and-condition', 'API\Customer\CustomerController@getCondition');
Route::get('v1/privacy-policy', 'API\Customer\CustomerController@getPrivacyPolicy');
Route::get('v1/payment-and-refund-policy', 'API\Customer\CustomerController@paymentRefundPolicy');
Route::post('v1/contact', 'API\Customer\CustomerController@contractUs');
Route::get('v1/destination-wise-checklist', 'API\Customer\CustomerController@getDestinationChicklist');
Route::get('v1/testimonials', 'API\Customer\CustomerController@getTestimonials');

//New Frontend routes
Route::get('tracking', [CustomerController::class, 'get_tracking_data']);
Route::get('frontend-content', [FrontendController::class, 'getFrontendContent']);
Route::get('slider', [FrontendController::class, 'getSlider']);
Route::post('contact', [FrontendController::class, 'contractUs']);
Route::get('destination', [FrontendController::class, 'getDestination']);
Route::get('destination-wise-checklist', [FrontendController::class, 'getDestinationChicklist']);


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('checklists-login-user', [CustomerController::class, 'get_checklist_login_user_data']);
    Route::get('my-profile', [CustomerController::class, 'myProfile']);
    Route::post('update-profile', [CustomerController::class, 'updateProfile']);
    Route::post('change-password', [CustomerController::class, 'change_password']);

    Route::post('application', [CustomerController::class, 'storeApp']);
    Route::get('application-data', [CustomerController::class, 'getAppData']);

    Route::post('file-upload', [CustomerController::class, 'fileUpload']);
    Route::post('file-update/{fileCode}', [CustomerController::class, 'fileUpdate']);
    Route::get('get-file-data', [CustomerController::class, 'getFileData']);

    Route::post('application-hold', [CustomerController::class, 'storeHoldApp']);

    Route::get('application-view/{id}', [CustomerController::class, 'viewAppData']);
    Route::get('application-edit/{id}', [CustomerController::class, 'editAppData']);
    Route::post('update', [CustomerController::class, 'update_data']);

    Route::delete('application-delete/{id}', [CustomerController::class, 'delete']);

    Route::get('visaTypeWiseCatWithChecklist', [CustomerController::class, 'visaTypeWiseCatWithChecklist']);
    Route::get('visaType', [CustomerController::class, 'getVisaType']);
    Route::get('visaTypeWiseCat/{id}', [CustomerController::class, 'VisaTypeWiseCat']);
    Route::get('visaCatWiseCheckList/{id}', [CustomerController::class, 'catWiseChecklist']);

    Route::post('visaStatusChange/{statusId}', [CustomerController::class, 'statusChange']);

    Route::post('missing-file', [CustomerController::class, 'missingFile']);
    Route::get('missing-file-list/{id}', [CustomerController::class, 'missingFileList']);

    // SSLCOMMERZ START
    Route::get('get-payment-date/{id}', 'SslCommerzPaymentController@hostedCheckout');
    Route::post('pay', [SslCommerzPaymentController::class, 'pay']);
});

Route::get('/test-ssl', [SslCommerzPaymentController::class, 'testSslCommerz']);
