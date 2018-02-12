<?php

Auth::routes();
Route::view('/', 'welcome');

Route::group(['middleware' => 'auth'], function () {
    // Account
    Route::get('/account/profile', 'AccountController@getProfile');
    Route::post('/account/profile', 'AccountController@postUpdateProfile');
    Route::get('/account/social-media', 'AccountController@getSocialMedia');

    // Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // Company routes
    Route::get('/companies', 'CompaniesController@index');
    Route::get('/companies/{company}/select', ['as' => 'set-company', 'uses' => 'CompaniesController@getSelect']);

    // Client routes
    Route::get('/clients', 'ClientsController@index');
    Route::get('/clients/{client}', 'ClientsController@show');

    // Work View Routes
    Route::get('/work', 'WorkController@index');
    Route::get('/work/month', ['as' => 'work-monthly', 'uses' => 'WorkController@getMonthView']);
    Route::get('/work/week', ['as' => 'work-weekly', 'uses' => 'WorkController@getWeekView']);
    Route::get('/work/day', ['as' => 'work-dayly', 'uses' => 'WorkController@getDayView']);

    // Ajax Routes
    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
        // Company
        Route::get('/companies/{id}', 'CompaniesController@show');
        Route::post('/companies', 'CompaniesController@store');
        Route::put('/companies/{company}', 'CompaniesController@update');

        // Client
        Route::post('/clients', 'ClientsController@store');

        // Work
        Route::post('/work', 'WorkController@store');
        Route::put('/work/{work}', 'WorkController@update');
        Route::delete('/work/{work}', 'WorkController@destroy');
    });
});
