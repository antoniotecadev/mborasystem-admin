<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProdutosMbora;
use Illuminate\Support\Facades\Log;

class ProdutosMboraController extends Controller
{
    public function store(Request $request)
    {
        $pm = new ProdutosMbora();
    
        try {
            if ($request->has(['imei', 'idcategoria', 'nome', 'preco', 'quantidade', 'urlImage', 'codigo_barra', 'tag'])) :
                $pm->imei = $request->imei;
                $pm->idcategoria = $request->idcategoria;
                $pm->nome = $request->nome;
                $pm->preco = $request->preco;
                $pm->quantidade = $request->quantidade;
                $pm->urlImage = $request->urlImage;
                $pm->codigoBarra = $request->codigo_barra;
                $pm->tag = $request->tag;
                $pm->save();
                return ['insert' => 'ok'];
            else :
                return ['insert' => 'erro', 'throwable' => 'Parâmetro de produto errado.'];
            endif;
        } catch (\Throwable $th) {
            Log::channel('daily')->emergency($th->getMessage());
            return ['insert' => 'erro', 'throwable' => 'Produto não enviado, ouvi uma falha de registo.'];
        }
    }
}
