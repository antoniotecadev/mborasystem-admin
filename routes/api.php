<?php

use Illuminate\Http\Request;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/termoscondicoes', function () {
    return Inertia::render('TermosCondicoes/Index');
});

Route::get('/politicaprivacidade', function () {
    return Inertia::render('PoliticaPrivacidade/Index');
})->name('politica.privacidade');

Route::namespace('Api')->group(function () {
    Route::get('/contacts/{imei}/estado', 'ContactsController@index');
    Route::post('/contacts', 'ContactsController@store');
    Route::get('/bairros', 'ContactsController@getBairros');
});
