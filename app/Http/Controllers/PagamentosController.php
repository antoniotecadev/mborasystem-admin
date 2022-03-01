<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Resources\PagamentoCollection;
use App\Http\Resources\PagamentoResource;
use App\Http\Resources\UserContactCollection;
use App\Http\Requests\PagamentoStoreRequest;
use App\Http\Requests\PagamentoUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;


class PagamentosController extends Controller
{
    public function index()
    {
        return Inertia::render('Pagamentos/Index', [
            'filters' => Request::all('search', 'trashed'),
            'pagamentos' => new PagamentoCollection(
                Auth::user()->account->pagamentos()
                    ->with('contact')
                    ->orderBy('id', 'desc')
                    ->filter(Request::only('search', 'trashed'))
                    ->paginate()
                    ->appends(Request::all()),
                ),
            'quantidade' => Pagamento::count(),
        ]);
    }

 public function create()
    {
        return Inertia::render('Pagamentos/Create', [
            'contacts' => new UserContactCollection(
                Auth::user()->account->contacts()
                    ->orderBy('id')
                    ->get()
            ),
        ]);
    }

    public function store(PagamentoStoreRequest $request)
    {
    if($this->tipoPacote($request->pacote, $request->tipo_pagamento) == $request->preco):

        $c = DB::table('contacts')
        ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
        ->where('contacts.id', $request->contact_id)
        ->latest('pagamentos.id')
        ->select('contacts.first_name', 'contacts.last_name', 'contacts.estado', 'pagamentos.fim')
        ->limit(1)
        ->get();

        if(empty($c['0'])):
            $this->activarParceiro($request->contact_id);
            Auth::user()->account->pagamentos()->create(
                $request->validated()
            );
            return Redirect::route('pagamentos')->with('success', 'Pagamento efectuado.');
        else:
            if($c['0']->estado == 0 && $c['0']->fim <= date('Y-m-d')):
                $this->activarParceiro($request->contact_id);
                Auth::user()->account->pagamentos()->create(
                    $request->validated()
                );
                return Redirect::route('pagamentos')->with('success', 'Pagamento efectuado (' .$c['0']->first_name.' '.$c['0']->last_name).')';
            else:
                return Redirect::route('pagamentos')->with('error', 'Pagamento não efectuado, ' .$c['0']->first_name.' '.$c['0']->last_name . ' já está activo ou possui um pagamento em uso.');
            endif;
        endif;

    else:
        return Redirect::route('pagamentos.create')->with('error', 'Seleccione o preço');
    endif;
    }


    public function edit($id)
    {
        return Inertia::render('Pagamentos/Edit', [
            'pagamento' => new PagamentoResource(Pagamento::withTrashed()->findOrFail(Crypt::decryptString($id))),
            'contacts' => new UserContactCollection(
                Auth::user()->account->contacts()
                    ->orderBy('id', 'desc')
                    ->get()
            ),
        ]);
    }

    public function update(Pagamento $pagamento, PagamentoUpdateRequest $request)
    {
        if($this->tipoPacote($request->pacote, $request->tipo_pagamento) == $request->preco):
            $pagamento->update(
                $request->validated()
            );
            return Redirect::back()->with('success', 'Pagamento actualizado.');
        else:
            return Redirect::route('pagamentos.edit', Crypt::encryptString($request->id))->with('error', 'Seleccione o preço');
        endif;

    }

    public function destroy(Pagamento $pagamento)
    {
        $pagamento->delete();

        return Redirect::back()->with('success', 'Pagamento eliminado.');
    }

    public function restore(Pagamento $pagamento)
    {
        $pagamento->restore();

        return Redirect::back()->with('success', 'Pagamento restaurado.');
    }

    private function activarParceiro($id){
        DB::table('contacts')
        ->where('id', $id)
        ->update(['contacts.estado' => '1']);
    }

    function tipoPacote($pacote, $tipo) {
        $tipo_pacote = [
          '0' => [
            '1'=> 3500,
            '3'=> 10500,
            '6'=> 21000,
            '12'=> 40000
          ],
          '1'=> [
            '1'=> 6500,
            '3'=> 19500,
            '6'=> 39000,
            '12'=> 75000
          ],
          '2'=> [
            '1'=> 12000,
            '3'=> 36000,
            '6'=> 72000,
            '12'=> 100000
          ]
        ];
        return $tipo_pacote[$pacote][$tipo];
      }
}
