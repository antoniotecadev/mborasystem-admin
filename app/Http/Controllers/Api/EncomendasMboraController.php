<?php

namespace App\Http\Controllers\Api;

use App\Class\Enc;
use App\Models\EncomendasMbora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EncomendasMboraController extends BaseController
{
    public function store(Request $request) {
        try {

            $validator = Validator::make($request->all(), [
                'client_phone' => 'required|min:9',
                'client_address' => 'required|max:50',
                'client_info_ad' => 'max:50',
                'imei_contacts' => 'required',
                'id_users_mbora' => 'required',
                'id_produtos_mbora' => 'required',
            ]);

            if($validator->fails()):
                $error['message'] = $validator->errors();
                return $this->sendError('Erro de validação', $error);
            endif;
            $request['id_users_mbora'] = Enc::desencriptar($request->id_users_mbora);
            EncomendasMbora::create($request->all());
            $success['message'] = 'encomendado(a)';

            return $this->sendResponse($success, 'Produto encomendado com sucesso');

        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Produto não encomendado', $error);
        }
    }
}
