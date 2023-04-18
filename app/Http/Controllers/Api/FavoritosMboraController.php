<?php

namespace App\Http\Controllers\Api;

use App\Models\FavoritosMbora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritosMboraController extends BaseController
{
    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'id_products_mbora' => 'required|numeric'
            ]);

            if($validator->fails()):
                $error['message'] = $validator->errors();
                $this->sendError('Erro de validação', $error); 
            endif;

            $request['id_users_mbora'] = auth()->user()->id;
            FavoritosMbora::create($request->all());
            $success['message'] = 'Favorito';
            return $this->sendResponse($success, 'Produto adicionado aos favoritos');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Produto não adicionado aos favoritos', $error);
        }
    }

    public function delete(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'id_products_mbora' => 'required|numeric'
            ]);

            if($validator->fails()):
                $error['message'] = $validator->errors();
                $this->sendError('Erro de validação', $error); 
            endif;

            FavoritosMbora::where('id_users_mbora', auth()->user()->id)->where('id_products_mbora', $request->id_products_mbora)->forceDelete();
            $success['message'] = 'Favorito';
            return $this->sendResponse($success, 'Produto eliminado dos favoritos');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Produto não eliminado dos favoritos', $error);
        }
    }

    public function iSfavorito($idProduto) {
        return FavoritosMbora::where('id_users_mbora', auth()->user()->id)->where('id_products_mbora', $idProduto)->get('id_products_mbora');
    }
}
