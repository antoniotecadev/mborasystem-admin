<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\SeguidoresEmpresasMbora;
use Illuminate\Http\Request;

class SeguidoresEmpresasMboraController extends Controller
{
    public function getNumberFollowers($imei) {
        $seguidores = Contact::where('imei', $imei)->first('followers_mbora');
        return $seguidores->followers_mbora;
    }

    public function followCompany($imei, $isFollower) {
        try {
            $id_user = auth()->user()->id;
            $estado = $isFollower == 'false';
            $seguidor = SeguidoresEmpresasMbora::where('imei_empresas_mbora', $imei)
                    ->where('id_users_mbora', $id_user)
                    ->update(['estado' => $estado]);
            if($seguidor == 0) {
                SeguidoresEmpresasMbora::create(['imei_empresas_mbora' => $imei, 'id_users_mbora' => $id_user, 'estado' => 1]);
                return ['success' => true, 'estado' => true];
            } else if($seguidor == 1) {
                return ['success' => true, 'estado' => $estado];
            }
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro ao tentar seguir ou não seguir', $error);
        }
        //OUTRA FORMA DE SEGUIR E NÃO SEGUIR
        // $seguidor = SeguidoresEmpresasMbora::where('imei_empresas_mbora', $imei)->where('id_users_mbora', $id_user)->first();
        // if($seguidor == null) {
        //     SeguidoresEmpresasMbora::create(['imei_empresas_mbora' => $imei, 'id_users_mbora' => $id_user, 'estado' => 1]);
        //     return ['estado' => true];
        // } else {
        //     $estado = $isFollower == 'false';
        //     SeguidoresEmpresasMbora::where('imei_empresas_mbora', $imei)
        //         ->where('id_users_mbora', $id_user)
        //         ->update(['estado' => $estado]);
        //     return ['estado' => $estado];
        // }
    }
}
