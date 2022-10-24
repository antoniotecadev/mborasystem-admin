<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class EquipasController extends Controller
{

    public function verificarCodigo($codigo){

        $codigoLength = Str::length($codigo);

        if($codigoLength == 6):
            $c = DB::table('equipas')
            ->join('agentes', 'agentes.equipa_id', '=', 'equipas.id')
            ->where('codigo', $codigo)
            ->limit(1)
            ->select('equipas.codigo', 'equipas.estado')
            ->get();
        else:
            return [[ 'codigo' => '', 'estado' => 0]];
        endif;

        if(empty($c['0'])) {
            return [[ 'codigo' => '', 'estado' => 0]];
        } else {
            return $c;
        }
    }

}
