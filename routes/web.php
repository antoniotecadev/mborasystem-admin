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

// Auth
Route::get('login')->name('login')->uses('Auth\LoginController@showLoginForm')->middleware('guest');
Route::post('login')->name('login.attempt')->uses('Auth\LoginController@login')->middleware('guest');
Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');

// Dashboard
Route::get('/')->name('dashboard')->uses('DashboardController')->middleware('auth');

// Users
Route::get('users')->name('users')->uses('UsersController@index')->middleware('remember', 'auth');
Route::get('users/create')->name('users.create')->uses('UsersController@create')->middleware('auth');
Route::post('users')->name('users.store')->uses('UsersController@store')->middleware('auth');
Route::get('users/{user}/edit')->name('users.edit')->uses('UsersController@edit')->middleware('auth');
Route::put('users/{user}')->name('users.update')->uses('UsersController@update')->middleware('auth');
Route::delete('users/{user}')->name('users.destroy')->uses('UsersController@destroy')->middleware('auth');
Route::put('users/{user}/restore')->name('users.restore')->uses('UsersController@restore')->middleware('auth');

// Images
Route::get('/img/{path}', 'ImagesController@show')->where('path', '.*');

// Contacts
Route::get('contacts')->name('contacts')->uses('ContactsController@index')->middleware('remember', 'auth');
Route::get('contacts/create')->name('contacts.create')->uses('ContactsController@create')->middleware('auth');
Route::post('contacts')->name('contacts.store')->uses('ContactsController@store')->middleware('auth');
Route::get('contacts/{contact}/edit')->name('contacts.edit')->uses('ContactsController@edit')->middleware('auth');
Route::put('contacts/{contact}')->name('contacts.update')->uses('ContactsController@update')->middleware('auth');
Route::delete('contacts/{contact}')->name('contacts.destroy')->uses('ContactsController@destroy')->middleware('auth');
Route::put('contacts/{contact}/restore')->name('contacts.restore')->uses('ContactsController@restore')->middleware('auth');
Route::put('contacts/{id}/estado')->name('contacts.estado')->uses('ContactsController@estadoUpdate')->middleware('auth');
Route::get('contacts/refresh')->name('contacts.refresh')->uses('ContactsController@refresh')->middleware('auth');

//Pagamentos
Route::get('pagamentos')->name('pagamentos')->uses('PagamentosController@index')->middleware('remember', 'auth');
Route::get('pagamentos/create')->name('pagamentos.create')->uses('PagamentosController@create')->middleware('auth');
Route::post('pagamentos')->name('pagamentos.store')->uses('PagamentosController@store')->middleware('auth');
Route::get('pagamentos/{pagamento}/edit')->name('pagamentos.edit')->uses('PagamentosController@edit')->middleware('auth');
Route::put('pagamentos/{pagamento}')->name('pagamentos.update')->uses('PagamentosController@update')->middleware('auth');
Route::delete('pagamentos/{pagamento}')->name('pagamentos.destroy')->uses('PagamentosController@destroy')->middleware('auth');
Route::put('pagamentos/{pagamento}/restore')->name('pagamentos.restore')->uses('PagamentosController@restore')->middleware('auth');

// Reports
Route::get('reports')->name('reports')->uses('ReportsController')->middleware('auth');

// 500 error
Route::get('500', function () {
    // echo $fail;
});
