<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\EncomendasMbora;
use App\Notifications\EncomendaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class EncomendasMboraController extends BaseController
{
    public function show($lastVisible, $isMoreView) {
        return DB::table('produtos_mbora', 'pm')
            ->join('encomendas_mbora as em', 'pm.id', '=', 'em.id_produts_mbora')
            ->join('contacts as ct', 'em.imei_contacts', '=', 'ct.imei')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->where('em.id_users_mbora', auth()->user()->id)
            ->where('em.id', ($isMoreView == 'false' ? '>' : '<') , ($isMoreView == 'false' ? 0 : $lastVisible)) // ORDEM DECRESCENTE
            ->select('em.id', 'em.prod_quant', 'em.estado', 'em.created_at', 'pm.nome', 'pm.preco', 'pm.urlImage', 'pm.codigoBarra', 'pm.visualizacao', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia')
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
                'id_produts_mbora' => 'required',
            ]);

            if($validator->fails()):
                $error['message'] = $validator->errors();
                return $this->sendError('Erro de validação', $error);
            endif;

            $array_product = array();
            $products = null;

            $user = auth()->user();
            $user_email = $user->email;
            $user_name = $user->first_name . ' ' . $user->last_name;
            
            $request['id_users_mbora'] = $user->id;

            DB::beginTransaction();
            $array_qty = $request['prod_quant'];
            $array_imei = $request['imei_contacts'];
            $array_id = $request['id_produts_mbora'];
            $client_phone = $request['client_phone'];
            $client_coordinate = $request['client_coordinate'];

            for ($i=0; $i < count($array_id); $i++) 
            { 
                $request['prod_quant'] = $array_qty[$i];
                $request['imei_contacts'] = $array_imei[$i];
                $request['id_produts_mbora'] = $array_id[$i];

                $array_product[$request['imei_contacts']][$request['id_produts_mbora']] = $request['product_name'][$i];

                // EncomendasMbora::create($request->all());
            }
            foreach ($array_product as $imei => $product) {
                foreach ($product as $name) {
                    $products .= $name . ', ';
                }
                $contact = Contact::where('imei', $imei)->first();
                $company_name = $contact->empresa;
                $owner_name = $contact->first_name . ' ' . $contact->last_name;
                Notification::send($contact, new EncomendaNotification($user_name, $user_email, $client_phone, $client_coordinate['latlng'], $products, $company_name, $owner_name));
                Notification::route('mail', ['antonioteca@hotmail.com' => $owner_name])->notify(new EncomendaNotification($user_name, $user_email, $client_phone, $client_coordinate['latlng'], $products, $company_name, $owner_name));
            }
            DB::commit();
            $success['message'] = 'encomendado(a)';
            return $this->sendResponse($success, 'Produto encomendado com sucesso');
        } catch (\Throwable $th) {
            DB::rollback();
            $error['message'] = $th->getMessage();
            return $this->sendError('Produto não encomendado', $error);
        }

    }

    public function getCountEncomenda() {
        return EncomendasMbora::where('id_users_mbora', auth()->user()->id)->count();
    }
}
