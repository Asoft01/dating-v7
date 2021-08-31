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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'IndexController@index');

Route::any('/register', 'UsersController@register');
Route::get('/profile/{username}', 'UsersController@viewProfile');

Route::group(['middleware'=> ['frontlogin']], function(){
    Route::any('/step/2', 'UsersController@step2');
    Route::any('/step/3', 'UsersController@step3');
    Route::get('/review', 'UsersController@review');    
    Route::get('/delete-photo/{photo}', 'UsersController@deletePhoto');    
    Route::get('/default-photo/{photo}', 'UsersController@defaultPhoto');    
});


Route::any('/login', 'UsersController@login');
Route::any('/logout', 'UsersController@logout');

Route::get('/check-email', 'UsersController@checkEmail');
Route::get('/check-username', 'UsersController@checkUsername');

Route::match(['get', 'post'], '/admin', 'AdminController@login');

// Route::get('/logout', 'AdminController@logout');
Route::get('/admin/logout', 'AdminController@logout');


Route::group(['middleware' => ['adminlogin']], function(){
    Route::get('/admin/dashboard', 'AdminController@dashboard');
    Route::get('/admin/settings', 'AdminController@settings');
    Route::get('/admin/check-pwd', 'AdminController@chkPassword');
    Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword');


    // Users Routes
    Route::get('admin/view-users', 'UsersController@viewUsers');
    Route::post('admin/update-user-status', 'UsersController@updateUserStatus');
    Route::post('admin/update-photo-status', 'UsersController@updatePhotoStatus');

});
