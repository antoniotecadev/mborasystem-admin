<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SeguidoresEmpresasMbora;
use Illuminate\Support\Facades\DB;

class SeguidoresEmpresasMboraController extends Controller
{
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

    public static function getNumberEmpresasAseguir() {
        return SeguidoresEmpresasMbora::where('id_users_mbora', auth()->user()->id)
                ->where('estado', 1)
                ->count();
    }

    public function followersCompany($imei, $lastVisible, $isMoreView) {
        $seguidores = DB::table('users as us')
            ->join('seguidores_empresas_mbora as sm', 'sm.id_users_mbora', '=', 'us.id')
            ->where('sm.imei_empresas_mbora', $imei)
            ->where('sm.estado', 1)
            ->where('sm.id', ($isMoreView == 'false' ? '>' : '<') , ($isMoreView == 'false' ? 0 : $lastVisible))
            ->select('sm.id as id_table_followers', 'us.first_name', 'us.last_name', 'us.photo_path')
            ->limit(10)
            ->orderByDesc('sm.created_at')
            ->get();
            return ['seguidor' => $seguidores, 'numeroSeguidor' => $isMoreView == 'true' ? 0 : $this->getNumberFollowers($imei)];
    }

    private function getNumberFollowers($imei) {
        return SeguidoresEmpresasMbora::where('imei_empresas_mbora', $imei)
                ->where('estado', 1)
                ->count();
    }
}
