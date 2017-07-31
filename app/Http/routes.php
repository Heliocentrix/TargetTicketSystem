<?php

// Remove Registration
Route::any('auth/register', function () {
    return abort(404);
});

// Auth controllers
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

// Javascript Validation
Route::get('/js/validation.js', 'AppController@js');

// Images
Route::get('img/{path}', 'AppController@glide')->where('path', '.+');

// Failsafe redirect
Route::any('home', function () {
    return redirect('/');
});

/*
|
| Authenticated Users
|
*/

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'AppController@index');
    Route::get('dashboard/maintenance', 'AppController@showMaintenance');

    // Api
    Route::post('api/ticketsort', 'TicketController@setOrder');
    Route::post('api/getclientinfo', 'UserController@getInfo');
    Route::get('api/move/ticket/{direction}/{user_id}/{ticket_id}/{archived}', 'TicketController@move');

    // Backend
    Route::get('maintenance', 'AppController@showMaintenance');
    Route::resource('clients', 'ClientsController');
    Route::put('clients/{client_id}/active', 'ClientsController@active')->name('clients.active');
    Route::resource('adverts', 'AdvertController');
    Route::resource('services', 'ServicesController');

    Route::get('documents/{type}', 'AdminDocumentsController@index');
    Route::get('documents/{type}/create', 'AdminDocumentsController@create');
    Route::post('documents/{type}', 'AdminDocumentsController@store');
    Route::get('documents/{type}/{id}', 'AdminDocumentsController@show');
    Route::delete('documents/{type}/{id}', 'AdminDocumentsController@destroy');

    Route::get('ticket_logs', 'TicketUpdateLogController@index');

    // Frontend
    Route::resource('{company_slug}/tickets', 'TicketController');
    Route::get('{company_slug}/tickets/{id}/archive/{archive}', 'TicketController@archive');
    Route::get('{company_slug}/tickets/{id}/respond/{value}', 'TicketController@respond');
    Route::post('{company_slug}/tickets/{id}/addresponse', 'TicketController@addResponse');
    Route::post('{company_slug}/tickets/{ticket_id}/{response_id}/edittime', 'TicketController@editResponseTime');

    Route::get('{company_slug}/documents/{type}', 'DocumentsController@index');
    Route::get('{company_slug}/secure_login', 'DocumentsController@secure_document_login')->name('doc.secure_login');
    
    Route::post('ticket_logs/export', 'TicketUpdateLogController@exportTicketLogs')->name('ticket_logs.export');
});
