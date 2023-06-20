<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\SeguidoresEmpresasMbora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmpresasMboraController extends BaseController
{
    public function index() {
        $user = auth()->user();
        $number = Contact::count();
        $numeroEmpresas = $number;
        if($number == 0):
            return [];
        elseif ($number > 10):
            $number = 10;
        endif;
        $empresas = DB::table('contacts as ct')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->select('ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'ct.views_mbora', 'ct.description', 'pv.nome as nomeProvincia')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('produtos_mbora')->whereColumn('imei', 'ct.imei')->whereNull('deleted_at');
            }, 'product_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('count(*)')->from('encomendas_mbora')->whereColumn('imei_contacts', 'ct.imei')->where('id_users_mbora', $user->id);
            }, 'encomenda_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('estado')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('id_users_mbora', $user->id)->limit(1);
            }, 'estado')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('estado', 1);
            }, 'followers_number')
            ->orderByDesc('ct.views_mbora')
            ->get()->random($number);
            return ['empresas' => $empresas, 'numeroEmpresas' => $numeroEmpresas];
    }

    public function searchCompany($nameImei, $isMoreCompany, $leastViewed) {
        return DB::table('contacts as ct')
            ->where('ct.empresa', 'LIKE', $nameImei . "%")
            ->orWhere('ct.imei', 'LIKE', $nameImei . "%")
            ->where(function($query) use($isMoreCompany, $leastViewed) {
                $query->where('ct.views_mbora', ($isMoreCompany == 'false' ? '>=' : '<') , ($isMoreCompany == 'false' ? 0 : $leastViewed)); // ORDEM DECRESCENTE
            })
            ->select('ct.id', 'ct.imei', 'ct.empresa')
            ->orderByDesc('ct.views_mbora')
            ->limit(20)
            ->get();
    }

    public function fetchCompany($nameImei, $isMoreCompany, $leastViewed) {
        $user = auth()->user();
        return DB::table('contacts as ct')
            ->where('ct.empresa', 'LIKE', $nameImei . "%")
            ->orWhere('ct.imei', 'LIKE', $nameImei . "%")
            ->where(function($query) use($isMoreCompany, $leastViewed) {
                $query->where('ct.views_mbora', ($isMoreCompany == 'false' ? '>=' : '<') , ($isMoreCompany == 'false' ? 0 : $leastViewed)); // ORDEM DECRESCENTE
            })
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->select('ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'ct.views_mbora', 'ct.description', 'pv.nome as nomeProvincia')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('produtos_mbora')->whereColumn('imei', 'ct.imei')->whereNull('deleted_at');
            }, 'product_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('count(*)')->from('encomendas_mbora')->whereColumn('imei_contacts', 'ct.imei')->where('id_users_mbora', $user->id);
            }, 'encomenda_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('estado')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('id_users_mbora', $user->id)->limit(1);
            }, 'estado')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('estado', 1);
            }, 'followers_number')
            ->orderByDesc('ct.views_mbora')
            ->limit(10)
            ->get();
    }

    public function numberViewsCompany($imei, $userIMEI){
        $empresa = Contact::where('imei', $imei)->first();
        if($imei != $userIMEI):
            $empresa->increment('views_mbora');
        endif;
        return ['views' => $empresa->views_mbora];
    }

    public function companyFollowers($lastVisible, $isMoreView) {
        $user = auth()->user();
        $empresas = DB::table('contacts as ct')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->join('seguidores_empresas_mbora as sm', 'sm.imei_empresas_mbora', '=', 'ct.imei')
            ->where('sm.id_users_mbora', $user->id)
            ->where('sm.estado', 1)
            ->where('sm.id', ($isMoreView == 'false' ? '>' : '<') , ($isMoreView == 'false' ? 0 : $lastVisible))
            ->select('sm.id as id_table_followers', 'ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'ct.views_mbora', 'ct.description', 'pv.nome as nomeProvincia')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('produtos_mbora')->whereColumn('imei', 'ct.imei')->whereNull('deleted_at');
            }, 'product_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('count(*)')->from('encomendas_mbora')->whereColumn('imei_contacts', 'ct.imei')->where('id_users_mbora', $user->id);
            }, 'encomenda_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('estado')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('id_users_mbora', $user->id)->limit(1);
            }, 'estado')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('estado', 1);
            }, 'followers_number')
            ->limit(10)
            ->orderByDesc('sm.id')
            ->get();
            return ['empresa' => $empresas, 'numeroEmpresasAseguir' => $isMoreView == 'true' ? 0 : SeguidoresEmpresasMboraController::getNumberEmpresasAseguir(), 'idEmpresaAseguirPaginacao' => $this->getIdEmpresaAseguir()];
    }

    private function getIdEmpresaAseguir() {
        $segundaEmpresaAseguir = SeguidoresEmpresasMbora::where('id_users_mbora', auth()->user()->id)
            ->skip(1)->take(1)->get('id');
        if ($segundaEmpresaAseguir->count() > 0) {
            return $segundaEmpresaAseguir[0]->id;
        }
    }

    public function getCompany($imei) {
        $user = auth()->user();
        return DB::table('contacts as ct')
            ->where('ct.imei', '=', $imei)
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->select('ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'ct.views_mbora', 'ct.description', 'pv.nome as nomeProvincia')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('produtos_mbora')->whereColumn('imei', 'ct.imei')->whereNull('deleted_at');
            }, 'product_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('count(*)')->from('encomendas_mbora')->whereColumn('imei_contacts', 'ct.imei')->where('id_users_mbora', $user->id);
            }, 'encomenda_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('estado')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('id_users_mbora', $user->id)->limit(1);
            }, 'estado')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('estado', 1);
            }, 'followers_number')
            ->limit(1)
            ->get();
    }

    public function getCompanyProfile() {
        $user = auth()->user();
        return DB::table('contacts as ct')
            ->where('ct.imei', '=', $user->imei_contact)
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->select('ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'ct.views_mbora', 'ct.description', 'pv.nome as nomeProvincia')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('produtos_mbora')->whereColumn('imei', 'ct.imei')->whereNull('deleted_at');
            }, 'product_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('count(*)')->from('encomendas_mbora')->whereColumn('imei_contacts', 'ct.imei')->where('imei_contacts', $user->imei_contact);
            }, 'encomenda_number')
            ->selectSub(function($query) use ($user) {
                $query->selectRaw('estado')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('imei_empresas_mbora', $user->imei_contact)->limit(1);
            }, 'estado')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('estado', 1);
            }, 'followers_number')
            ->limit(1)
            ->get();
    }

    private function getProvinceId($provincia){
        return DB::table('provincias')->where('nome', $provincia)->first('id');
    }

    public function update(Request $request) {
        try {
            if($request->action == 5):
                $request['provincia_id'] = $this->getProvinceId($request->nomeProvincia)->id;
            endif;
            $column = [
                1 => ['empresa' => $request->empresa],
                2 => ['description' => $request->description],
                3 => ['email' => $request->email],
                4 => ['phone' => $request->phone, 'alternative_phone' => $request->alternative_phone],
                5 => [
                        'provincia_id' => $request->provincia_id, 
                        'district' => $request->district,
                        'street' => $request->street,
                ],
            ];

            $columnValidator = [
                1 => ['empresa' => 'required|string|min:4|max:20'],
                2 => ['description' => 'required|string|max:30'],
                3 => ['email' => 'required|email|max:50'],
                4 => [
                        'phone' => 'required|min:9|regex:/^([0-9\s\-\+\(\)]*)$/', 
                        'alternative_phone' => 'required|min:9|regex:/^([0-9\s\-\+\(\)]*)$/'
                    ],
                5 => [
                        'provincia_id' => 'required|integer', 
                        'district' => 'required|string|min:4|max:20',
                        'street' => 'required|string|min:4|max:20',
                ],
            ];
            
            $validator = Validator::make($request->all(), $columnValidator[$request->action]);
            if($validator->fails()) {
                $error['message'] = $validator->errors();
                return $this->sendError('Erro de validação', $error); 
            }
            Contact::where('imei', auth()->user()->imei_contact)->update($column[$request->action]);
            $success['empresa'] =  $request->empresa;
            return $this->sendResponse($success, 'Alteração feita');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500); 
        }
    }

    public function updateProfilePhoto(Request $request) {
        try {
            $validator = Validator::make($request->all(),[
                'photoURL' => 'required|url',
            ]);
            if($validator->fails()) {
                $error['message'] = $validator->errors();
                return $this->sendError('Erro de validação', $error); 
            }
            Contact::where('imei', auth()->user()->imei_contact)->update([
                'photo_path' => $request->photoURL,
            ]);
            $success['message'] =  null;
            return $this->sendResponse($success, 'Foto de perfil alterada');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500 ); 
        }
    }

    public function getPathProfilePhoto() {
        return Contact::where('imei', auth()->user()->imei_contact)->first('photo_path');        
    }
}
