<?php

Auth::routes();
Route::view('/', 'welcome');
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => 'auth'], function () {
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

    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
        Route::post('/companies', 'CompaniesController@store');
    });
});
