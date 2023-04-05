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

Auth::routes();

Route::get('/profiles/{user}', 'ProfileController@show')->name('profile.show');
Route::get('/profiles/{user}/edit', 'ProfileController@edit')->name('profile.edit');
Route::patch('/profiles/{user}', 'ProfileController@update')->name('profile.update');

Route::get('/p/create', 'PostController@create');
Route::get('/p/{post}', 'PostController@show');
Route::post('/p', 'PostController@store');
