<?php

namespace App\Http\Controllers\Api;

use App\Class\Enc;
use App\Models\EncomendasMbora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EncomendasMboraController extends BaseController
{
    public function show($lastVisible, $isMoreView) {
        return DB::table('produtos_mbora', 'pm')
            ->join('encomendas_mbora as em', 'pm.id', '=', 'em.id_produtos_mbora')
            ->join('contacts as ct', 'em.imei_contacts', '=', 'ct.imei')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->where('em.id_users_mbora', auth()->user()->id)
            ->where('em.id', ($isMoreView == 'false' ? '>' : '<') , ($isMoreView == 'false' ? 0 : $lastVisible)) // ORDEM DECRESCENTE
            ->select('em.id', 'em.estado', 'em.created_at', 'pm.nome', 'pm.preco', 'pm.urlImage', 'pm.codigoBarra', 'pm.visualizacao', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia')
            ->orderByDesc('em.created_at') // Remover ao usar ordem CRESCENTE
            ->limit(2)
            ->get();

            /** ORDEM CRESCENTE
            * ->where('em.id', '>' , ($isMoreView == 'true' ? $lastVisible : 0)) 
            */
    }

    public function store(Request $request) {
        try {

            $validator = Validator::make($request->all(), [
                'client_phone' => 'required|min:9',
                'client_address' => 'required|max:50',
                'client_info_ad' => 'max:50',
                'imei_contacts' => 'required',
                'id_produtos_mbora' => 'required',
            ]);

            if($validator->fails()):
                $error['message'] = $validator->errors();
                return $this->sendError('Erro de validação', $error);
            endif;
            $request['id_users_mbora'] = auth()->user()->id;
            EncomendasMbora::create($request->all());
            $success['message'] = 'encomendado(a)';
            return $this->sendResponse($success, 'Produto encomendado com sucesso');

        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Produto não encomendado', $error);
        }
    }

    public function getCountEncomenda() {
        return EncomendasMbora::where('id_users_mbora', auth()->user()->id)->count();
    }
}
