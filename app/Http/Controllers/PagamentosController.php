<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Inertia\Inertia;
use App\Models\Contact;
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
                    ->appends(Request::all())
            ),
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
        $c = DB::table('contacts')
        ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
        ->where('contacts.id', $request->contact_id)
        ->latest('pagamentos.id')
        ->select('contacts.first_name', 'contacts.last_name', 'contacts.estado', 'pagamentos.fim')
        ->limit(1)
        ->get();

        if($c['0']->estado == 0 && $c['0']->fim <= date('Y-m-d')){

            Auth::user()->account->pagamentos()->create(
                $request->validated()
            );

            return Redirect::route('pagamentos')->with('success', 'Pagamento efectuado ' .$c['0']->first_name.' '.$c['0']->last_name);
        } else {
            return Redirect::route('pagamentos')->with('error', 'Pagamento não efectuado, ' .$c['0']->first_name.' '.$c['0']->last_name . ' já está activo ou possui um pagamento em uso.');
        }
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
}
