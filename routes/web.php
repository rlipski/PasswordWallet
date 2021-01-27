<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
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

Route::get('register', 'App\Http\Controllers\Auth\RegisterController@register')->name('register');
Route::post('register', 'App\Http\Controllers\Auth\RegisterController@storeUser');
Route::get('editMasterPassword', 'App\Http\Controllers\Auth\RegisterController@editMasterPassword')->name('editMasterPassword');
Route::post('editMasterPassword', 'App\Http\Controllers\Auth\RegisterController@saveMasterPassword')->name('saveMasterPassword');

Route::get('login', 'App\Http\Controllers\Auth\LoginController@login')->name('login');
Route::post('login', 'App\Http\Controllers\Auth\LoginController@authenticate');
Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::get('home', 'App\Http\Controllers\Password\PasswordController@home')->name('home');

Route::get('password/create', 'App\Http\Controllers\Password\PasswordController@create')->name('createPassword');
Route::post('password/create', 'App\Http\Controllers\Password\PasswordController@storePassword')->name('storePassword');
Route::get('password/decryptPassword/{id}', 'App\Http\Controllers\Password\PasswordController@decryptPassword')->name('decryptPassword');
Route::get('password/delete/{id}', 'App\Http\Controllers\Password\PasswordController@delete')->name('deletePassword');
