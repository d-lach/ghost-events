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

Auth::routes();

Route::get('/', function () {
    return redirect()->route('events.list');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('events')
    ->group(function () {
        Route::get('', [
            'as' => 'events.list',
            'uses' => 'events\EventsController@eventsList'
        ]);

        Route::get('map', [
            'as' => 'events.map',
            'uses' => 'events\EventsController@eventsMap'
        ]);

        Route::middleware('auth')
            ->group(function() {
                Route::get('mine', [
                    'as' => 'events.userEvents',
                    'uses' => 'events\EventsController@userEvents'
                ]);

                Route::get('attend', [
                    'as' => 'events.userAsGuestEvents',
                    'uses' => 'events\EventsController@userAsGuestEvents'
                ]);

                Route::get('new', [
                    'as' => 'events.creator',
                    'uses' => 'events\EventsController@eventNew'
                ]);

                Route::get('{eventId}/edit', [
                    'as' => 'events.editor',
                    'uses' => 'events\EventsController@eventEdit'
                ]);
            });

        Route::get('{eventId}', [
            'as' => 'events.fullPage',
            'uses' => 'events\EventsController@getEvent'
        ]);
    });

