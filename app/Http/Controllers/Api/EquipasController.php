<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Resources\ContactResource;
use Illuminate\Support\Facades\DB;

class EquipasController extends Controller
{

    public function verificarCodigo($codigo){
        $c = DB::table('equipas')
        ->join('agentes', 'agentes.equipa_id', '=', 'equipas.id')
        ->where('codigo', $codigo)
        ->limit(1)
        ->select('equipas.codigo', 'equipas.estado')
        ->get();

        if(empty($c['0'])) {
            return [[ 'codigo' => '', 'estado' => 0]];
        } else {
            return $c;
        }
    }

}
