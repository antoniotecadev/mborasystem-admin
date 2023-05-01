<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class EmpresasMboraController extends Controller
{
    public function index() {
        return DB::table('contacts as ct')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->select('ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'ct.followers_mbora', 'ct.views_mbora')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('produtos_mbora')->whereColumn('imei', 'ct.imei');
            }, 'product_number')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('encomendas_mbora')->whereColumn('imei_contacts', 'ct.imei')->where('id_users_mbora', auth()->user()->id);
            }, 'encomenda_number')
            ->selectSub(function($query) {
                $query->selectRaw('estado')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('id_users_mbora', auth()->user()->id)->limit(1);
            }, 'estado')
            ->orderByDesc('ct.followers_mbora')
            ->get()->random(10);
    }

    public function searchCompany($nameImei, $isMoreCompany, $leastViewed) {
        return DB::table('contacts as ct')
            ->where('ct.empresa', 'LIKE', $nameImei . "%")
            ->orWhere('ct.imei', 'LIKE', $nameImei . "%")
            ->where(function($query) use($isMoreCompany, $leastViewed) {
                $query->where('ct.views_mbora', ($isMoreCompany == 'false' ? '>=' : '<') , ($isMoreCompany == 'false' ? 0 : $leastViewed)); // ORDEM DECRESCENTE
            })
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->select('ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'ct.followers_mbora', 'ct.views_mbora')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('produtos_mbora')->whereColumn('imei', 'ct.imei');
            }, 'product_number')
            ->selectSub(function($query) {
                $query->selectRaw('count(*)')->from('encomendas_mbora')->whereColumn('imei_contacts', 'ct.imei')->where('id_users_mbora', auth()->user()->id);
            }, 'encomenda_number')
            ->selectSub(function($query) {
                $query->selectRaw('estado')->from('seguidores_empresas_mbora')->whereColumn('imei_empresas_mbora', 'ct.imei')->where('id_users_mbora', auth()->user()->id)->limit(1);
            }, 'estado')
            ->orderByDesc('ct.views_mbora')
            ->limit(10)
            ->get();
    }

    public function numberViewsCompany($imei){
        $empresa = Contact::where('imei', $imei)->first();
        $empresa->increment('views_mbora');
        return ['views' => $empresa->views_mbora];
    }
}
