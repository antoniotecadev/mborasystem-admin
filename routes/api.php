<?php

use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/termoscondicoes', function () {
    return Inertia::render('TermosCondicoes/Index');
});

Route::get('/politicaprivacidade', function () {
    return Inertia::render('PoliticaPrivacidade/Index');
})->name('politica.privacidade');

Route::namespace('Api')->group(function () {
    Route::get('/contacts/{imei}/estado', 'ContactsController@index')->where('imei', '[0-9]+');
    Route::post('/contacts', 'ContactsController@store');
    Route::get('{provincia}/municipios', 'ContactsController@getMunicipios');
    Route::get('{municipio}/bairros', 'ContactsController@getBairros');
    Route::get('/equipas/{codigo}/verificar', 'EquipasController@verificarCodigo')->where('codigo', '[0-9]+');
    Route::get('/contacts/contactos', 'ContactsController@getContactos');
    Route::get('view/lista/equipas')->name('api.lista.equipas')->uses('EquipasController@getListaEquipas');
    Route::get('view/{equipa}/rendimento/equipas/{codigo}')->name('api.rendimento.equipas')->uses('EquipasController@rendimentoEquipas');
    Route::get('view/login/equipa/{dds1}/{dds2}/{dds3}')->name('api.login.equipa')->uses('EquipasController@loginEquipa');
    Route::get('equipas/{equipa}/editar/{codigo}/codigo/{inicio}/inicio/{fim}/fim/{numero}/agente/{percentagem}')->name('api.calcular.rendimento.equipa')->uses('EquipasController@calcularRendimentoEquipa');

    Route::get('categorias/mbora', 'CategoriasMboraController@index');
    Route::post('produtos/mbora/store', 'ProdutosMboraController@store');
    Route::get('produtos/mbora/{imei}', 'ProdutosMboraController@getQuantidadeProduto');
    Route::get('produtos/mbora/index/json', 'ProdutosMboraController@index');
    Route::get('produtos/mbora/searchproduct/{nome}', 'ProdutosMboraController@searchProduct');
    Route::get('produtos/mbora/view/count/{id}', 'ProdutosMboraController@getViewNumberProduct');
    
    Route::post('auth/register', 'AuthController@create');
    Route::post('auth/login', 'AuthController@login');

    
    Route::middleware('auth:sanctum')->group(function() {
        Route::post('user/autenticated', function() {
            $user = Auth::user();
            $bc = new BaseController();
            $success['message'] = 'Usuário autenticado';
            $success['name'] =  $user->first_name . ' ' . $user->last_name;
            $success['email'] =  $user->email;
            return $bc->sendResponse($success, 'Autenticado');
        });
        Route::get('encomendas/mbora/count/{id_users_mbora}', 'EncomendasMboraController@getCountEncomenda');
        Route::get('encomendas/mbora/{id_users_mbora}/lastVisible/{lastVisible}/isMoreView/{isMoreView}', 'EncomendasMboraController@show');
        Route::post('produtos/mbora/encomenda', 'EncomendasMboraController@store');
        Route::post('mbora/logout/user', 'AuthController@logout');
    });
});
