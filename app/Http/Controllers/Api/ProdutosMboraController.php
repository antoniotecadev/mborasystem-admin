<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProdutosMbora;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ContactsController;

class ProdutosMboraController extends BaseController
{
    public function index() {
        $date = date('Y-m-d');
        $number = ProdutosMbora::count();
        $numeroProdutos = $number;
        if($number == 0):
            return [];
        elseif ($number > 32):
            $number = 32;
        endif;
        $produtos = DB::table('produtos_mbora as pm')
            ->whereDate('pm.created_at', '<=', $date)
            ->join('contacts as ct', 'pm.imei', '=', 'ct.imei')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->join('categorias_mbora as cm', 'cm.id', '=', 'pm.idcategoria')
            ->select('pm.id', 'pm.imei', 'pm.idcategoria', 'pm.nome', 'pm.preco', 'pm.quantidade', 'pm.urlImage', 'pm.codigoBarra', 'pm.tag', 'pm.visualizacao', 'pm.created_at', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'cm.nome as nomeCategoria')
            ->selectSub(function($query) {
                $query->selectRaw('id_products_mbora')->from('favoritos_mbora')->whereColumn('id_products_mbora', 'pm.id')->where('id_users_mbora', auth()->user()->id)->limit(1);
            }, 'isFavorito')
            ->orderByDesc('pm.created_at')
            ->get()->random($number);
            return ['produtos' => $produtos, 'numeroProdutos' => $numeroProdutos];
    }

    public function showProductCategory($idcategoria, $isTag, $tag) {
        $date = date('Y-m-d');
        $column = $isTag == 'true' ? ['name' => 'tag', 'value' => '%' . $tag . '%', 'operator' => 'LIKE'] : ['name' => 'idcategoria', 'value' => $idcategoria, 'operator' => '='];
        $number = ProdutosMbora::where($column['name'], $column['operator'], $column['value'])->count();
        $numeroProdutos = $number;
        if($number == 0):
            return [];
        elseif ($number > 32):
            $number = 32;
        endif;

        $produtos = DB::table('produtos_mbora as pm')
            ->where($column['name'], $column['operator'], $column['value'])
            ->whereDate('pm.created_at', '<=', $date)
            ->join('contacts as ct', 'pm.imei', '=', 'ct.imei')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->join('categorias_mbora as cm', 'cm.id', '=', 'pm.idcategoria')
            ->select('pm.id', 'pm.imei', 'pm.idcategoria', 'pm.nome', 'pm.preco', 'pm.quantidade', 'pm.urlImage', 'pm.codigoBarra', 'pm.tag', 'pm.visualizacao', 'pm.created_at', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'cm.nome as nomeCategoria')
            ->selectSub(function($query) {
                $query->selectRaw('id_products_mbora')->from('favoritos_mbora')->whereColumn('id_products_mbora', 'pm.id')->where('id_users_mbora', auth()->user()->id)->limit(1);
            }, 'isFavorito')
            ->orderByDesc('pm.created_at')
            ->get()->random($number);
            return ['produtos' => $produtos, 'numeroProdutos' => $numeroProdutos];
    }

    public function searchProduct($name, $isMoreProduct, $leastViewed) {
        return DB::table('produtos_mbora as pm')
            ->where('pm.nome', 'LIKE', $name . "%")
            ->where(function($query) use($isMoreProduct, $leastViewed) {
                $query->where('pm.visualizacao', ($isMoreProduct == 'false' ? '>=' : '<') , ($isMoreProduct == 'false' ? 0 : $leastViewed)); // ORDEM DECRESCENTE
            })
            ->join('contacts as ct', 'pm.imei', '=', 'ct.imei')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->join('categorias_mbora as cm', 'cm.id', '=', 'pm.idcategoria')
            ->select('pm.id', 'pm.imei', 'pm.idcategoria', 'pm.nome', 'pm.preco', 'pm.quantidade', 'pm.urlImage', 'pm.codigoBarra', 'pm.tag', 'pm.visualizacao', 'pm.created_at', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'cm.nome as nomeCategoria')
            ->selectSub(function($query) {
                $query->selectRaw('id_products_mbora')->from('favoritos_mbora')->whereColumn('id_products_mbora', 'pm.id')->where('id_users_mbora', auth()->user()->id)->limit(1);
            }, 'isFavorito')
            ->orderByDesc('pm.visualizacao')
            ->limit(10)
            ->get();
    }

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
        return ProdutosMbora::where('imei', $imei)->count();
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

    public function getViewNumberProduct($id){
        $produto = ProdutosMbora::findOrFail($id);
        $produto->increment('visualizacao');
        return ['view' => $produto->visualizacao];
    }

    public function showProductServiceCompany($lastVisible, $isMoreView, $imei) {
        $produtos = DB::table('produtos_mbora', 'pm')
            ->join('contacts as ct', 'pm.imei', '=', 'ct.imei')
            ->join('provincias as pv', 'pv.id', '=', 'ct.provincia_id')
            ->join('categorias_mbora as cm', 'cm.id', '=', 'pm.idcategoria')
            ->where('pm.imei', $imei)
            ->where('pm.id', ($isMoreView == 'false' ? '>' : '<') , ($isMoreView == 'false' ? 0 : $lastVisible)) // ORDEM DECRESCENTE
            ->whereNull('pm.deleted_at')
            ->select('pm.id', 'pm.imei', 'pm.idcategoria', 'pm.nome', 'pm.preco', 'pm.quantidade', 'pm.urlImage', 'pm.codigoBarra', 'pm.tag', 'pm.visualizacao', 'pm.created_at', 'ct.imei', 'ct.empresa', 'ct.district', 'ct.street', 'pv.nome as nomeProvincia', 'cm.nome as nomeCategoria')
            ->selectSub(function($query) {
                $query->selectRaw('id_products_mbora')->from('favoritos_mbora')->whereColumn('id_products_mbora', 'pm.id')->where('id_users_mbora', auth()->user()->id)->limit(1);
            }, 'isFavorito')
            ->orderByDesc('pm.id')
            ->limit(10)
            ->get();
        
            return ['produtoServico' => $produtos, 'idProdutoServico' => $this->getIdProdutoServico($imei)];
    }

    private function getIdProdutoServico($imei) {
        $segundoProduto = ProdutosMbora::where('imei', $imei)->skip(1)->take(1)->get('id');
        if ($segundoProduto->count() > 0) {
            return $segundoProduto[0]->id;
        }
    }

    public function getNumberProductServiceCompany($imei) {
        return ProdutosMbora::where('imei', $imei)->count();
    }

    public function deleteProductService(Request $request) {
        try {
            ProdutosMbora::where('id', $request->productId)->where('imei', $request->companyImei)->delete();
            $success['message'] = 'Produto | Serviço';
            return $this->sendResponse($success, 'Produto | Serviço eliminado(a)');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Produto | Serviço não eliminado(a)', $error);
        }
    }
}
