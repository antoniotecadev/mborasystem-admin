<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\EncomendasMbora;
use App\Models\User;
use App\Notifications\EncomendaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class EncomendasMboraController extends BaseController
{
    public function show($lastVisible, $isMoreView) {
        $encomendas = DB::table('produtos_mbora', 'pm')
            ->join('encomendas_mbora as em', 'pm.id', '=', 'em.id_produts_mbora')
            ->join('contacts as ct', 'em.imei_contacts', '=', 'ct.imei')
            ->join('users as us', 'em.id_users_mbora', '=', 'us.id')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->where('em.id_users_mbora', auth()->user()->id)
            ->where('em.id', ($isMoreView == 'false' ? '>' : '<') , ($isMoreView == 'false' ? 0 : $lastVisible)) // ORDEM DECRESCENTE
            ->select('em.id', 'em.client_phone', 'em.client_address', 'em.client_info_ad','em.client_coordinate', 'em.code', 'em.prod_quant', 'em.estado', 'em.created_at', 'pm.nome', 'pm.preco', 'pm.urlImage', 'pm.codigoBarra', 'pm.visualizacao', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'ct.photo_path', 'ct.coordinate as company_coordinate', 'us.first_name', 'us.last_name', 'us.email', 'pv.nome as nomeProvincia')
            ->orderByDesc('em.id') // Remover ao usar ordem CRESCENTE
            ->limit(10)
            ->get();

        return ['encomenda' => $encomendas, 'numeroEncomenda' => $isMoreView == 'true' ? 0 : $this->getNumberEncomenda(), 'numeroEmpresasAseguir' => $isMoreView == 'true' ? 0 : SeguidoresEmpresasMboraController::getNumberEmpresasAseguir(), 'idEncomendaPaginacao' => $this->getIdEncomendaForIDuser()];
            /** ORDEM CRESCENTE
            * ->where('em.id', '>' , ($isMoreView == 'true' ? $lastVisible : 0)) 
            */
    }

    private function getIdEncomendaForIDuser() {
        $segundaEncomenda = EncomendasMbora::where('id_users_mbora', auth()->user()->id)->skip(1)->take(1)->get('id');
        if ($segundaEncomenda->count() > 0) {
            return $segundaEncomenda[0]->id;
        }
    }

    public function showInCompanyProfile($imei, $lastVisible, $isMoreView) {
        $user = auth()->user();
        $encomendas = DB::table('produtos_mbora', 'pm')
            ->join('encomendas_mbora as em', 'pm.id', '=', 'em.id_produts_mbora')
            ->join('contacts as ct', 'em.imei_contacts', '=', 'ct.imei')
            ->join('users as us', 'em.id_users_mbora', '=', 'us.id')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->when(($imei != $user->imei_contact), function($query) use ($user) {
                return $query->where('em.id_users_mbora', $user->id);
            })
            ->where('em.imei_contacts', $imei)
            ->where('em.id', ($isMoreView == 'false' ? '>' : '<') , ($isMoreView == 'false' ? 0 : $lastVisible))
            ->select('em.id', 'em.client_phone', 'em.client_address', 'em.client_info_ad','em.client_coordinate', 'em.code', 'em.prod_quant', 'em.estado', 'em.created_at', 'pm.nome', 'pm.preco', 'pm.urlImage', 'pm.codigoBarra', 'pm.visualizacao', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'ct.coordinate as company_coordinate', 'us.first_name', 'us.last_name', 'us.email', 'us.photo_path', 'pv.nome as nomeProvincia')
            ->orderByDesc('em.id')
            ->limit(10)
            ->get();
        return ['encomenda' => $encomendas, 'idEncomenda' => $this->getIdEncomendaForIMEI($imei)];    
    }

    private function getIdEncomendaForIMEI($imei) {
        $segundaEncomenda = EncomendasMbora::where('imei_contacts', $imei)->skip(1)->take(1)->get('id');
        if ($segundaEncomenda->count() > 0) {
            return $segundaEncomenda[0]->id;
        }
    }

    public function store(Request $request) {
        try {

            $validator = Validator::make($request->all(), [
                'client_phone' => 'required|min:9|regex:/^([0-9\s\-\+\(\)]*)$/',
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

            $seed = date('YmdHis');
            srand($seed);
            $random_code = rand();

            for ($i=0; $i < count($array_id); $i++) 
            { 
                $request['code'] = $random_code;
                $request['prod_quant'] = $array_qty[$i];
                $request['imei_contacts'] = $array_imei[$i];
                $request['id_produts_mbora'] = $array_id[$i];

                $array_product[$request['imei_contacts']][$request['id_produts_mbora']] = $request['product_name'][$i];

                EncomendasMbora::create($request->all());
            }
            foreach ($array_product as $imei => $product) {
                foreach ($product as $name) {
                    $products .= $name . ', ';
                }
                $contact = Contact::where('imei', $imei)->first();
                $company_name = $contact->empresa;
                $owner_name = $contact->first_name . ' ' . $contact->last_name;
                $company_coordinate = json_decode($contact->coordinate);
                Notification::send($contact, new EncomendaNotification($user_name, $user_email, $client_phone, $client_coordinate['latlng'], $products, $company_name, $owner_name, $company_coordinate->latlng));
                Notification::route('mail', [$contact->email => $owner_name])->notify(new EncomendaNotification($user_name, $user_email, $client_phone, $client_coordinate['latlng'], $products, $company_name, $owner_name, $company_coordinate->latlng));
            }
            $exponentPushTokens = User::whereIn('imei_contact', $array_imei)->whereNotNull('exponentPushToken')->pluck('exponentPushToken');
            DB::commit();
            $success['user_name'] = $user_name;
            $success['exponentPushTokens'] = $exponentPushTokens;
            return $this->sendResponse($success, 'encomendado(a)');
        } catch (\Throwable $th) {
            DB::rollback();
            $error['message'] = $th->getMessage();
            return $this->sendError('Produto não encomendado', $error);
        }

    }

    private function getNumberEncomenda() {
        return EncomendasMbora::where('id_users_mbora', auth()->user()->id)->count();
    }

    public function getNumberCompanyProfileEncomenda($imei) {
        $user = auth()->user();
        return EncomendasMbora::when(($imei != $user->imei_contact), function($query) use ($user) {
                return $query->where('id_users_mbora', $user->id);
            })
            ->where('imei_contacts', $imei)
            ->count();
    }

    public function markAsAnswered(Request $request) {
        try {
            $imei = auth()->user()->imei_contact;
            EncomendasMbora::where('imei_contacts', $imei)
                ->where('code', $request->code)
                ->update(['estado' => '1']);
            $success['message'] =  null;
            return $this->sendResponse($success, 'Marcada como etendida');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500); 
        }
    }

    public function getNotifications($lastVisible, $isMoreView) {
        $imei = auth()->user()->imei_contact;
        $contact = Contact::where('imei', $imei)->first();
        $notificacao = $contact->notifications
                        ->take(10)
                        ->each(function($notication){
                            $notication->formatted_created_at = $notication->created_at->format('d-m-Y h:m');
                        })
                        ->where('notification_id', ($isMoreView == 'false' ? '>' : '<') , ($isMoreView == 'false' ? 0 : $lastVisible));
        return ['notificacao' => $notificacao, 'numeroNotificacoesNaolida' => $this->getUnreadNotificationsNumber($contact), 'numeroTotalNotificacoes' => $isMoreView == 'true' ? 0 : $this->getNotificationsNumberTotal($contact), 'idNotificacao' => $this->getIdNotification($contact)];
    }

    public static function getUnreadNotificationsNumber($contact) {
        return $contact->unreadNotifications->count();
    }

    private function getNotificationsNumberTotal($contact) {
        return $contact->notifications->count();
    }
    
    private function getIdNotification($contact) {
        $segundaNotificacao = $contact->notifications->skip(1)->take(1);
        if ($segundaNotificacao->count() > 0) {
            return $segundaNotificacao['1']->notification_id;
        }
    }

    public function markAsRead(Request $request) {
        try {
            $contact = Contact::where('imei', auth()->user()->imei_contact)->first();
            $contact->notifications()->where('id', $request->idNotification)->update(['read_at' => now()]);
            $success['message'] =  'Para ver os detalhes desta encomenda vai em encomendas no perfil da empresa.';
            return $this->sendResponse($success, 'Notificação de encomenda');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500); 
        }
    }
}
