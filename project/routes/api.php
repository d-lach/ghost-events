<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('events')
    ->group(function () {

        Route::get('all/{page}', [
            'as' => 'events.page',
            'uses' => 'events\EventsApiController@allPaginated'
        ]);

        Route::get('all', [
            'as' => 'events.allAvailable',
            'uses' => 'events\EventsApiController@all'
        ]);

        Route::middleware('auth:api')
            ->group(function () {
                Route::post('', [
                    'as' => 'events.store',
                    'uses' => 'events\EventsApiController@store'
                ]);

                Route::get('mine', [
                    'as' => 'events.hostedByUser',
                    'uses' => 'events\EventsApiController@mine'
                ]);

                Route::get("all-mine-ids", [
                    'as' => 'events.userEventsIds',
                    'uses' => 'events\EventsApiController@getIdsOfUserEvents'
                ]);

                Route::put('{eventId}/update', [
                    'as' => 'events.update',
                    'uses' => 'events\EventsApiController@update'
                ]);

                Route::delete('{eventId}/destroy', [
                    'as' => 'events.destroy',
                    'uses' => 'events\EventsApiController@destroy'
                ]);

                Route::get('{eventId}/', [
                    'as' => 'events.show',
                    'uses' => 'events\EventsApiController@show'
                ]);

                Route::post('{eventId}/join', [
                    'as' => 'events.join',
                    'uses' => 'events\EventsApiController@join'
                ]);

                Route::post('{eventId}/leave', [
                    'as' => 'events.leave',
                    'uses' => 'events\EventsApiController@leave'
                ]);

                Route::post('{eventId}/invite', [
                    'as' => 'events.invite',
                    'uses' => 'events\InvitationsController@invite'
                ]);

                Route::post('{eventId}/guests/add', [
                    'as' => 'events.invite',
                    'uses' => 'events\EventsApiController@addGuest'
                ]);

                Route::post('{eventId}/guests/remove', [
                    'as' => 'events.invite',
                    'uses' => 'events\EventsApiController@removeGuest'
                ]);

            });
    });