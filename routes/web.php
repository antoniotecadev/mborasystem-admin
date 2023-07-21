<?php

use Illuminate\Support\Facades\Artisan;
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

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear'); 
    $exitCode = Artisan::call('config:clear');
    return 'DONE';
 });

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
Route::get('contacts/create/municipio/{municipio}')->name('contacts.bairros')->uses('ContactsController@getBairros')->middleware('auth');
Route::post('contacts')->name('contacts.store')->uses('ContactsController@store')->middleware('auth');
Route::get('contacts/{contact}/edit/{type}/{read_contact}')->name('contacts.edit')->uses('ContactsController@edit')->middleware('auth');
Route::put('contacts/{contact}')->name('contacts.update')->uses('ContactsController@update')->middleware('auth');
Route::delete('contacts/{contact}/motivo/{motivo}')->name('contacts.destroy')->uses('ContactsController@destroy')->middleware('auth');
Route::put('contacts/{contact}/restore')->name('contacts.restore')->uses('ContactsController@restore')->middleware('auth');
Route::put('contacts/{id}/estado')->name('contacts.estado')->uses('ContactsController@estadoUpdate')->middleware('auth');
Route::get('contacts/refresh')->name('contacts.refresh')->uses('ContactsController@refresh')->middleware('auth');
Route::get('contacts/notifications/registo/{type}')->name('contacts.notification')->uses('ContactsController@indexContactNotification')->middleware('auth');
Route::put('contacts/notifications/registo/{contact}/marcar/{type}/local/{local}/name/{name}')->name('contacts.notification.marcar')->uses('ContactsController@marcarNotificacao')->middleware('auth');

// Pagamentos
Route::get('pagamentos')->name('pagamentos')->uses('PagamentosController@index')->middleware('remember', 'auth');
Route::get('pagamentos/create')->name('pagamentos.create')->uses('PagamentosController@create')->middleware('auth');
Route::post('pagamentos')->name('pagamentos.store')->uses('PagamentosController@store')->middleware('auth');
Route::get('pagamentos/{pagamento}/edit')->name('pagamentos.edit')->uses('PagamentosController@edit')->middleware('auth');
Route::put('pagamentos/{pagamento}')->name('pagamentos.update')->uses('PagamentosController@update')->middleware('auth');
Route::delete('pagamentos/{pagamento}/motivo/{motivo}')->name('pagamentos.destroy')->uses('PagamentosController@destroy')->middleware('auth');
Route::put('pagamentos/{pagamento}/restore')->name('pagamentos.restore')->uses('PagamentosController@restore')->middleware('auth');

// Equipas
Route::get('equipas')->name('equipas')->uses('EquipasController@index')->middleware('remember', 'auth');
Route::get('equipas/create')->name('equipas.create')->uses('EquipasController@create')->middleware('auth');
Route::post('equipas')->name('equipas.store')->uses('EquipasController@store')->middleware('auth');
Route::get('equipas/{equipa}/edit')->name('equipas.edit')->uses('EquipasController@edit')->middleware('auth');
Route::put('equipas/{equipa}')->name('equipas.update')->uses('EquipasController@update')->middleware('auth');
Route::delete('equipas/{equipa}/motivo/{motivo}')->name('equipas.destroy')->uses('EquipasController@destroy')->middleware('auth');
Route::put('equipas/{equipa}/restore')->name('equipas.restore')->uses('EquipasController@restore')->middleware('auth');
Route::put('equipas/{id}/estado')->name('equipas.estado')->uses('EquipasController@estadoUpdate')->middleware('auth');
Route::get('equipas/{equipa}/editar/{codigo}/codigo/{inicio}/inicio/{fim}/fim/{numero}/agente/{percentagem}')->name('equipas.calcular')->uses('EquipasController@calcularRendimentoEquipa')->middleware('auth');
Route::put('equipas/{equipa}/update')->name('password.update')->uses('EquipasController@updatePassword')->middleware('auth');

// Agentes
Route::get('agentes')->name('agentes')->uses('AgentesController@index')->middleware('remember', 'auth');
Route::get('agentes/create')->name('agentes.create')->uses('AgentesController@create')->middleware('auth');
Route::post('agentes')->name('agentes.store')->uses('AgentesController@store')->middleware('auth');
Route::get('agentes/{agente}/edit')->name('agentes.edit')->uses('AgentesController@edit')->middleware('auth');
Route::put('agentes/{agente}')->name('agentes.update')->uses('AgentesController@update')->middleware('auth');
Route::delete('agentes/{agente}/motivo/{motivo}')->name('agentes.destroy')->uses('AgentesController@destroy')->middleware('auth');
Route::put('agentes/{agente}/restore')->name('agentes.restore')->uses('AgentesController@restore')->middleware('auth');

// Reports
Route::get('reports')->name('reports')->uses('ReportsController')->middleware('auth');

// 500 error
Route::get('500', function () {
    // echo $fail;
});
