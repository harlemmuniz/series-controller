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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', 'LoginController@index');

Route::get('/series', 'SeriesController@index')->name('list_series');
Route::get('/series/add', 'SeriesController@add')->name('form_add_serie')->middleware('authenticator');
Route::post('/series/add', 'SeriesController@store')->middleware('authenticator');
Route::delete('/series/{id}', 'SeriesController@destroy')->middleware('authenticator');
Route::post('/series/{id}/editname', 'SeriesController@editName')->middleware('authenticator');

Route::get('/series/{serieId}/seasons', 'SeasonsController@index');

Route::get('/seasons/{season}/episodes', 'EpisodesController@index');
Route::post('/seasons/{season}/episodes/watch', 'EpisodesController@watch')->middleware('authenticator');

Route::get('/login', 'LoginController@index')->name('login');
Route::post('/login', 'LoginController@login');

Route::get('/signin', 'SignInController@create');
Route::post('/signin', 'SignInController@store');

Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/login');
});

Route::get('/home', 'HomeController@index');
