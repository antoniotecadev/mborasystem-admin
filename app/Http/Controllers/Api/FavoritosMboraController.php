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
}
