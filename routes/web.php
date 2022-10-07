<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
// Start: website routes
/* Landing Page Route */

Route::get('/', 'HomeController@index')->name('home.verfied');

/* Registeration Route */
Route::get('registration/{type}', 'Auth\RegisterController@create')->name('registeration');
Route::post('registration', 'Auth\RegisterController@store')->name('register.store');

/* Login Route */
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Auth::routes(['verify' => true]);

/* Admin only access these routes*/
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['role:admin', 'prevent-back-history']], function () {
    Route::get('dashboard', 'HomeController@index')->name('admin.dashboard');
    // roles
    Route::resource('roles', 'RoleController');
    // users
    Route::resource('users', 'UserController');
    //blogs
    Route::resource('blogs', 'BlogController');
});

// End: website routes
