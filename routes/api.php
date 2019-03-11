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


// todo we should add passport authentication letter on
Route::resource('events', 'events\EventsApiController', ['except' => ['index', 'show']]);
//Route::middleware('auth:api')
//    ->resource('events', 'events\EventsApiController', ['except' => ['index']]);
