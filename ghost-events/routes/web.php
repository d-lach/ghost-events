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

Route::prefix('invitations')
    ->group(function () {
        Route::get('confirm/{confirmationToken}', [
            'as' => 'invitation.confirmation',
            'uses' => 'InvitationsController@invitationAccepted'
        ]);
    });

Route::prefix('events')
    ->group(function () {
        Route::get('', [
            'as' => 'events.list',
            'uses' => 'EventsController@eventsList'
        ]);

        Route::get('map', [
            'as' => 'events.map',
            'uses' => 'EventsController@eventsMap'
        ]);

        Route::middleware('auth')
            ->group(function () {
                Route::get('mine', [
                    'as' => 'events.userEvents',
                    'uses' => 'EventsController@userEvents'
                ]);

                Route::get('attend', [
                    'as' => 'events.userAsGuestEvents',
                    'uses' => 'EventsController@userAsGuestEvents'
                ]);

                Route::get('new', [
                    'as' => 'events.creator',
                    'uses' => 'EventsController@eventNew'
                ]);

                Route::get('{eventId}/edit', [
                    'as' => 'events.editor',
                    'uses' => 'EventsController@eventEdit'
                ]);
            });

        Route::get('{eventId}', [
            'as' => 'event.page',
            'uses' => 'EventsController@getEvent'
        ]);
    });
