<?php

Auth::routes();
Route::view('/', 'welcome');

Route::group(['middleware' => 'auth'], function () {
    // Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // Company routes
    Route::get('/companies', 'CompaniesController@index');
    Route::get('/companies/{company}/select', ['as' => 'set-company', 'uses' => 'CompaniesController@getSelect']);

    // Render base clients view
    Route::get('/clients', 'ClientsController@index');

    // Work View Routes
    Route::get('/work', 'WorkController@index');
    Route::get('/work/month', ['as' => 'work-monthly', 'uses' => 'WorkController@getMonthView']);
    Route::get('/work/week', ['as' => 'work-weekly', 'uses' => 'WorkController@getWeekView']);
    Route::get('/work/day', ['as' => 'work-dayly', 'uses' => 'WorkController@getDayView']);

    // Ajax Routes
    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
        Route::get('/companies/{id}', 'CompaniesController@find');
        Route::post('/companies', 'CompaniesController@store');
        Route::put('/companies/{company}', 'CompaniesController@update');
    });
});
