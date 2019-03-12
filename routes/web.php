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


/*Route::get('/events/list', '');
Route::get('/events/map', 'EventsController@eventsMap');*/


Route::get('/events',[
    'as' => 'events.list',
    'uses' => 'events\EventsController@eventsList'
]);

Route::get('/events/map',[
    'as' => 'events.map',
    'uses' => 'events\EventsController@eventsMap'
]);

Route::get('/', function () {
    return redirect()->route('events.list');
});

Route::get('/home', 'HomeController@index')->name('home');
// middleware('auth')->


//Route::post('/events2', 'events\EventsApiController@store');

