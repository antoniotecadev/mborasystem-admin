<?php

namespace App\Http\Controllers\Api;

use App\Models\FavoritosMbora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FavoritosMboraController extends BaseController
{
    public function show($lastVisible, $isMoreView) {
        $favorito = DB::table('produtos_mbora', 'pm')
            ->join('favoritos_mbora as fm', 'pm.id', '=', 'fm.id_products_mbora')
            ->join('users as us', 'fm.id_users_mbora', '=', 'us.id')
            ->join('contacts as ct', 'pm.imei', '=', 'ct.imei')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->join('categorias_mbora as cm', 'cm.id', '=', 'pm.idcategoria')
            ->where('fm.id_users_mbora', auth()->user()->id)
            ->where('fm.id', ($isMoreView == 'false' ? '>' : '<') , ($isMoreView == 'false' ? 0 : $lastVisible)) // ORDEM DECRESCENTE
            ->select('fm.id as idFavorito', 'pm.id', 'pm.imei', 'pm.idcategoria', 'pm.nome', 'pm.preco', 'pm.quantidade', 'pm.urlImage', 'pm.codigoBarra', 'pm.tag', 'pm.visualizacao', 'pm.created_at', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'cm.nome as nomeCategoria')
            ->selectSub(function($query) {
                $query->selectRaw('id_products_mbora')->from('favoritos_mbora')->whereColumn('id_products_mbora', 'pm.id')->where('id_users_mbora', auth()->user()->id)->limit(1);
            }, 'isFavorito')
            ->orderByDesc('fm.created_at')
            ->limit(10)
            ->get();
        return ['favorito' => $favorito, 'numeroFavorito' => $isMoreView == 'true' ? 0 : $this->getNumberFavorito()];
    }

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

    private function getNumberFavorito() {
        return FavoritosMbora::where('id_users_mbora', auth()->user()->id)->count();
    }
}
