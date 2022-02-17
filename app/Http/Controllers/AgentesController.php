<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Resources\AgenteCollection;
use App\Http\Resources\AgenteResource;
use App\Http\Resources\UserEquipaCollection;
use App\Http\Requests\AgenteStoreRequest;
use App\Http\Requests\PagamentoUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;


class AgentesController extends Controller
{
    public function index()
    {
        return Inertia::render('Agentes/Index', [
            'filters' => Request::all('search', 'trashed'),
            'agentes' => new AgenteCollection(
                Auth::user()->account->agentes()
                    ->with('equipa')
                    ->orderBy('id', 'desc')
                    ->filter(Request::only('search', 'trashed'))
                    ->paginate()
                    ->appends(Request::all())
            ),
        ]);
    }

 public function create()
    {
        return Inertia::render('Agentes/Create', [
            'equipas' => new UserEquipaCollection(
                Auth::user()->account->equipas()
                    ->orderBy('id')
                    ->get()
            ),
        ]);
    }

    public function store(AgenteStoreRequest $request)
    {
        Auth::user()->account->agentes()->create(
            $request->validated()
        );
        return Redirect::route('agentes')->with('success', 'Agente criado(a) 😊');
    }


    public function edit($id)
    {
        return Inertia::render('Agentes/Edit', [
            'agente' => new AgenteResource(Agente::withTrashed()->findOrFail(Crypt::decryptString($id))),
            'equipas' => new UserEquipaCollection(
                Auth::user()->account->equipas()
                    ->orderBy('id', 'desc')
                    ->get()
            ),
        ]);
    }

    public function update(Pagamento $pagamento, PagamentoUpdateRequest $request)
    {
        $pagamento->update(
            $request->validated()
        );

        return Redirect::back()->with('success', 'Pagamento actualizado.');
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
}
