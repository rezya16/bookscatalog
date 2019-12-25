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

Route::resource('book','BooksController');
Route::post('book/update','BooksController@update')->name('book.update');
Route::get('book/destroy/{id}', 'BooksController@destroy');

Route::resource('author','AuthorsController');
Route::post('author/update','AuthorsController@update')->name('author.update');
Route::get('author/destroy/{id}', 'AuthorsController@destroy');
