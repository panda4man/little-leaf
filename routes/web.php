<?php

Auth::routes();
Route::view('/', 'welcome');
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => 'auth'], function () {
    // Render base companies view
    Route::get('/companies', 'CompaniesController@index');

    // Render base clients view
    Route::get('/clients', 'ClientsController@index');

    // Work View Routes
    Route::get('/work/month', 'WorkController@getMonthView');
    Route::get('/work/week', 'WorkController@getWeekView');
    Route::get('/work/day', 'WorkController@getDayView');

    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
        Route::post('/companies', 'CompaniesController@store');
    });
});
