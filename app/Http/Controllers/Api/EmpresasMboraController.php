<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresasMboraController extends Controller
{
    public function index() {
        return DB::table('contacts as ct')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->select('ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'ct.followers_mbora', 'ct.views_mbora')
            ->orderByDesc('ct.followers_mbora')
            ->get()->random(10);
    }

    public function searchCompany($name, $isMoreCompany, $leastViewed) {
        return DB::table('contacts as ct')
            ->where('ct.empresa', 'LIKE', $name . "%")
            ->orWhere('ct.imei', 'LIKE', $name . "%")
            ->where(function($query) use($isMoreCompany, $leastViewed) {
                $query->where('ct.views_mbora', ($isMoreCompany == 'false' ? '>=' : '<') , ($isMoreCompany == 'false' ? 0 : $leastViewed)); // ORDEM DECRESCENTE
            })
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->select('ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'ct.followers_mbora', 'ct.views_mbora')
            ->orderByDesc('ct.views_mbora')
            ->limit(10)
            ->get();
    }
}
