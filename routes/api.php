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

Route::middleware('auth:api')
    ->resource('events', 'events\EventsApiController', ['except' => ['index', 'edit', 'create']]);
//Route::resource('events', 'events\EventsApiController', ['except' => ['index', 'edit', 'create']]);

Route::prefix('events')
    ->middleware('auth:api')
    ->group(function () {

        Route::post('{eventId}/join',[
            'as' => 'events.join',
            'uses' => 'events\EventsApiController@join'
        ]);

        Route::post('{eventId}/leave',[
            'as' => 'events.leave',
            'uses' => 'events\EventsApiController@leave'
        ]);

        Route::post('{eventId}/invite',[
            'as' => 'events.invite',
            'uses' => 'events\EventsApiController@invite'
        ]);
    });

//Route::middleware('auth:api')
//    ->resource('events', 'events\EventsApiController', ['except' => ['index']]);
