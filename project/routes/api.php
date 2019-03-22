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
            'uses' => 'EventsApiController@allPaginated'
        ]);

        Route::get('all', [
            'as' => 'events.allAvailable',
            'uses' => 'EventsApiController@all'
        ]);

        Route::middleware('auth:api')
            ->group(function () {
                Route::post('', [
                    'as' => 'events.store',
                    'uses' => 'EventsApiController@store'
                ]);

                Route::get('mine', [
                    'as' => 'events.hostedByUser',
                    'uses' => 'EventsApiController@mine'
                ]);

                Route::get("all-mine-ids", [
                    'as' => 'events.userEventsIds',
                    'uses' => 'EventsApiController@getIdsOfUserEvents'
                ]);

                Route::put('{eventId}/update', [
                    'as' => 'events.update',
                    'uses' => 'EventsApiController@update'
                ]);

                Route::delete('{eventId}/destroy', [
                    'as' => 'events.destroy',
                    'uses' => 'EventsApiController@destroy'
                ]);

                Route::get('{eventId}/', [
                    'as' => 'events.show',
                    'uses' => 'EventsApiController@show'
                ]);

                Route::post('{eventId}/join', [
                    'as' => 'events.join',
                    'uses' => 'EventsApiController@join'
                ]);

                Route::post('{eventId}/leave', [
                    'as' => 'events.leave',
                    'uses' => 'EventsApiController@leave'
                ]);

                Route::post('{eventId}/invite', [
                    'as' => 'events.invite',
                    'uses' => 'InvitationsController@invite'
                ]);

                Route::post('{eventId}/guests/add', [
                    'as' => 'events.addGuests',
                    'uses' => 'EventsApiController@addGuest'
                ]);

                Route::post('{eventId}/guests/remove', [
                    'as' => 'events.removeGuests',
                    'uses' => 'EventsApiController@removeGuest'
                ]);

            });
    });