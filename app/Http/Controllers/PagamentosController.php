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


class PagamentosController extends Controller
{
    public function index()
    {
        return Inertia::render('Pagamentos/Index', [
            'filters' => Request::all('search', 'trashed'),
            'pagamentos' => new PagamentoCollection(
                Auth::user()->account->pagamentos()
                    ->with('contact')
                    ->orderBy('id')
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
        Auth::user()->account->pagamentos()->create(
            $request->validated()
        );

        return Redirect::route('pagamentos')->with('success', 'Pagamento efectuado.');
    }

    public function edit($id)
    {
        return Inertia::render('Pagamentos/Edit', [
            'pagamento' => new PagamentoResource(Pagamento::withTrashed()->findOrFail(Crypt::decryptString($id))),
            'contacts' => new UserContactCollection(
                Auth::user()->account->contacts()
                    ->orderBy('id')
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
