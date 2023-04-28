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
            ->select('ct.id', 'ct.first_name', 'ct.last_name', 'ct.email', 'ct.phone', 'ct.alternative_phone', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'followers_mbora')
            ->orderByDesc('ct.followers_mbora')
            ->get()->random(10);
    }
}
