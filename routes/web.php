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

// Authentication Routes...
// $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
// $this->post('login', 'Auth\LoginController@login');
// $this->post('logout', 'Auth\LoginController@logout')->name('logout');


// Route::group(['prefix' => 'view'], function() {});

Route::redirect('categories/1', '/');

Route::get('entries/{id}', 'EntryController@show')->name('entry');
Route::get('categories/{id}', 'CategoryController@show')->name('category');

Route::get('/', 'HomeController@home')->name('home');
Route::get('random/{page}', 'HomeController@random')->name('random');

Route::view('rights', 'rights', [
    'background' => with(new \App\Models\Image)->getFull(372),
])->name('rights');
