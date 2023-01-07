<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProdutosMbora;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ContactsController;

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
            return ['insert' => 'erro', 'throwable' => 'Produto não enviado, ouvi uma falha de registo.'];
        }
    }

    public function getQuantidade($imei){
        return DB::table('produtos_mbora')
        ->where('imei', $imei)
        ->get()
        ->count();
    }

    public function getQuantidadeProduto($imei) {

        $ct = new ContactsController();
        
        $c = DB::table('contacts')
            ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
            ->where('imei', $imei)
            ->latest('pagamentos.id')   
            ->select('pagamentos.pacote', 'pagamentos.tipo_pagamento')
            ->limit(1)
            ->get();
            
        return [[ 
            'quantidade_produto_pacote' => $ct->getQuantidadeProdutoPacote($c['0']->pacote, $c['0']->tipo_pagamento),
            'quantidade_produto' => $this->getQuantidade($imei)
            ]];
    }
}
