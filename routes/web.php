<?php

Auth::routes();
Route::view('/', 'welcome');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'account'], function () {
        // Account
        Route::get('/profile', 'AccountController@getProfile');
        Route::post('/profile', 'AccountController@postUpdateProfile');
        Route::get('/social-media', 'AccountController@getSocialMedia');

        // Company routes
        Route::get('/companies', 'CompaniesController@index');
        Route::get('/companies/{company}/select', ['as' => 'set-company', 'uses' => 'CompaniesController@getSelect']);

        // Client routes
        Route::get('/clients', 'ClientsController@index');
        Route::get('/clients/{client}', 'ClientsController@show');
    });

    // Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // Work View
    Route::get('/work', 'WorkController@index');
    Route::get('/work/month', ['as' => 'work-monthly', 'uses' => 'WorkController@getMonthView']);
    Route::get('/work/week', ['as' => 'work-weekly', 'uses' => 'WorkController@getWeekView']);
    Route::get('/work/day', ['as' => 'work-dayly', 'uses' => 'WorkController@getDayView']);

    // Tasks
    Route::get('/tasks', 'TasksController@index');

    // Projects
    Route::get('/projects', 'ProjectsController@index');
    Route::get('/projects/{project}', 'ProjectsController@show');

    // Ajax Routes
    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
        // Company
        Route::get('/companies/{id}', 'CompaniesController@show');
        Route::post('/companies', 'CompaniesController@store');
        Route::put('/companies/{company}', 'CompaniesController@update');

        // Clients
        Route::apiResource('/clients', 'ClientsController', ['only' => ['index', 'store', 'update', 'destroy']]);

        // Deliverables
        Route::get('/deliverables/{deliverable}/hours-worked', 'DeliverablesController@getWorkDone');
        Route::post('/deliverables/{deliverable}/complete', 'DeliverablesController@postComplete');
        Route::apiResource('/deliverables', 'DeliverablesController', ['only' => ['store', 'update', 'destroy']]);

        // Tasks
        Route::apiResource('/tasks', 'TasksController', ['only' => ['store', 'update', 'destroy']]);

        // Work
        Route::apiResource('/work', 'WorkController', ['only' => ['store', 'update', 'destroy']]);

        // Projects
        Route::get('/projects/{project}/hours-worked', 'ProjectsController@getWorkDone');
        Route::apiResource('/projects', 'ProjectsController', ['only' => ['store', 'update', 'destroy']]);
    });
});
