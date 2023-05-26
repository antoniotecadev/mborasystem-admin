<?php

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\SendCodeResetPasswordController;
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
    Route::get('produtos/mbora/view/count/{id}', 'ProdutosMboraController@getViewNumberProduct');
    
    Route::post('auth/register', 'AuthController@create');
    Route::post('auth/login', 'AuthController@login');
    Route::get('mbora/find/account/user/{email}', 'AuthController@findAccount');
    
    Route::post('mbora/send/code/reset/password',  'SendCodeResetPasswordController@sendCodeResetPassword');
    Route::post('mbora/code/check/reset',  'CodeCheckController@codeCheck');
    Route::put('mbora/reset/password',  'ResetPasswordController@resetPassword');
    
    Route::get('number/visitas/empresas/mbora/imei/{imei}', 'EmpresasMboraController@numberViewsCompany');
    
    Route::middleware('auth:sanctum')->group(function() {
        Route::post('user/autenticated', function() {
            $user = Auth::user();
            $bc = new BaseController();
            $success['message'] = 'Usuário autenticado';
            $success['first_name'] =  $user->first_name;
            $success['last_name'] =  $user->last_name;
            $success['telephone'] =  $user->telephone;
            $success['email'] =  $user->email;
            return $bc->sendResponse($success, 'Autenticado');
        });
        Route::get('encomendas/mbora/lastVisible/{lastVisible}/isMoreView/{isMoreView}', 'EncomendasMboraController@show');
        Route::post('produtos/mbora/encomenda', 'EncomendasMboraController@store');
        
        Route::get('number/encomendas/empresas/mbora/imei/{imei}', 'EncomendasMboraController@getNumberCompanyEncomenda');
        Route::get('empresas/encomendas/mbora/imei/{imei}/lastVisible/{lastVisible}/isMoreView/{isMoreView}', 'EncomendasMboraController@showMyInCompany');
        
        Route::post('adicionar/produto/mbora/favorito', 'FavoritosMboraController@store');
        Route::delete('eliminar/produto/mbora/favorito', 'FavoritosMboraController@delete');
        Route::get('produtos/favorito/mbora/lastVisible/{lastVisible}/isMoreView/{isMoreView}', 'FavoritosMboraController@show');
        
        Route::put('mbora/update/name/user', 'AuthController@updateName');
        Route::put('mbora/update/email/user', 'AuthController@updateEmail');
        Route::put('mbora/update/password/user', 'AuthController@updatePassword');
        Route::put('mbora/update/profilephoto/user', 'AuthController@updateProfilePhoto');
        Route::get('mbora/profilephoto/user/url', 'AuthController@getURLProfilePhoto');
        Route::post('mbora/logout/user', 'AuthController@logout');
        
        Route::get('produtos/mbora/index/json', 'ProdutosMboraController@index');
        Route::get('produtos/mbora/categoria/{idCategoria}/isTag/{isTag}/tag/{tag}', 'ProdutosMboraController@showProductCategory');
        Route::get('number/produtos/servicos/mbora/imei/{imei}', 'ProdutosMboraController@getNumberProductServiceCompany');
        Route::get('produtos/mbora/searchproduct/{name}/isMoreProduct/{isMoreProduct}/leastViewed/{leastViewed}', 'ProdutosMboraController@searchProduct');
        Route::get('produtos/servicos/mbora/lastVisible/{lastVisible}/isMoreView/{isMoreView}/imei/{imei}', 'ProdutosMboraController@showProductServiceCompany');
        
        Route::get('seguir/empresas/mbora/imei/{imei}/isFollower/{isFollower}', 'SeguidoresEmpresasMboraController@followCompany');
        Route::get('seguidores/mbora/empresa/imei/{imei}/lastVisible/{lastVisible}/isMoreView/{isMoreView}', 'SeguidoresEmpresasMboraController@followersCompany');

        Route::get('empresas/mbora', 'EmpresasMboraController@index');
        Route::get('empresas/mbora/searchcompany/{nameImei}/isMoreCompany/{isMoreCompany}/leastViewed/{leastViewed}', 'EmpresasMboraController@searchCompany');
        Route::get('empresas/mbora/searchcompany/search/{nameImei}/isMoreCompany/{isMoreCompany}/leastViewed/{leastViewed}', 'EmpresasMboraController@fetchCompany');
        Route::get('empresas/mbora/aseguir/lastVisible/{lastVisible}/isMoreView/{isMoreView}', 'EmpresasMboraController@companyFollowers');
        Route::get('empresas/mbora/imei/{imei}', 'EmpresasMboraController@getCompany');
    });
});
