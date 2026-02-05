<?php

use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Route;
use Modules\Frontend\Http\Controllers\BranchContactController;


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

Route::prefix('frontend')->group(function () {

    //about us
    Route::get('/aboutus', 'FrontendController@index')->name('aboutus');
    Route::group(['prefix' => 'aboutus', 'as' => 'aboutus.'], function () {
        Route::post('datatable-data', 'FrontendController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'FrontendController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'FrontendController@edit')->name('edit');
        Route::post('delete', 'FrontendController@delete')->name('delete');
    });

    //faq
    Route::get('/faq', 'FaqController@index')->name('faq');
    Route::group(['prefix' => 'faq', 'as' => 'faq.'], function () {
        Route::post('datatable-data', 'FaqController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'FaqController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'FaqController@edit')->name('edit');
        Route::post('delete', 'FaqController@delete')->name('delete');
    });

    //service
    Route::get('/service', 'ServiceController@index')->name('service');
    Route::group(['prefix' => 'service', 'as' => 'service.'], function () {
        Route::post('datatable-data', 'ServiceController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'ServiceController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'ServiceController@edit')->name('edit');
        Route::post('delete', 'ServiceController@delete')->name('delete');
    });

    //slider
    Route::get('/slider', 'SliderController@index')->name('slider');
    Route::group(['prefix' => 'slider', 'as' => 'slider.'], function () {
        Route::post('datatable-data', 'SliderController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'SliderController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'SliderController@edit')->name('edit');
        Route::post('delete', 'SliderController@delete')->name('delete');
    });

    //privacy policy
    Route::get('/privacy', 'PrivacyController@index')->name('privacyPolicy');
    Route::group(['prefix' => 'privacy', 'as' => 'privacyPolicy.'], function () {
        Route::post('datatable-data', 'PrivacyController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'PrivacyController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'PrivacyController@edit')->name('edit');
    });

    //terms & condition
    Route::get('terms', 'TermsController@index')->name('terms');
    Route::group(['prefix' => 'terms', 'as' => 'terms.'], function () {
        Route::post('datatable-data', 'TermsController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'TermsController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'TermsController@edit')->name('edit');
    });

    //Contact us
    Route::get('/contactus', 'ContactusController@index')->name('contactus');
    Route::group(['prefix' => 'contactus', 'as' => 'contactus.'], function () {
        Route::post('datatable-data', 'ContactusController@getDataTableData')->name('datatable.data');
        Route::post('view', 'ContactusController@view')->name('view');
        Route::post('delete', 'ContactusController@delete')->name('delete');
    });

    // Popluar Destination
    Route::get('/destination', 'DestinationController@index')->name('destination');
    Route::group(['prefix' => 'destination', 'as' => 'destination.'], function () {
        Route::post('datatable-data', 'DestinationController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'DestinationController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'DestinationController@edit')->name('edit');
        Route::post('delete', 'DestinationController@delete')->name('delete');
    });

    //Testimonials
    Route::get('/testimonials', 'TestimonialController@index')->name('testimonials');
    Route::group(['prefix' => 'testimonials', 'as' => 'testimonials.'], function () {
        Route::post('datatable-data', 'TestimonialController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'TestimonialController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'TestimonialController@edit')->name('edit');
        Route::post('delete', 'TestimonialController@delete')->name('delete');
    });

    //slider
    Route::get('/branch-contact', [BranchContactController::class, 'index'])->name('branch.contact');
    Route::group(['prefix' => 'branch-contact', 'as' => 'branch.contact.'], function () {
        Route::post('datatable-data', [BranchContactController::class, 'getDataTableData'])->name('datatable.data');
        Route::post('store-or-update', [BranchContactController::class, 'storeOrUpdate'])->name('store.or.update');
        Route::post('edit', [BranchContactController::class, 'edit'])->name('edit');
        Route::post('delete', [BranchContactController::class, 'delete'])->name('delete');
    });
});
