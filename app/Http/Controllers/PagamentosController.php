<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Inertia\Inertia;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Resources\PagamentoCollection;
use App\Http\Resources\UserContactCollection;
use App\Http\Requests\PagamentoStoreRequest;
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

    public function estadoUpdate($id){
        $c = Contact::findOrFail(Crypt::decryptString($id));
        $c->estado = $c->estado == '0' ? '1' : '0' ;
        $c->save();
        return Redirect::route('contacts')->with('success', 'Confirmado');
    }
}
