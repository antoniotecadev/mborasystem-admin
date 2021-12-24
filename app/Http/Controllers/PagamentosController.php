<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Resources\PagamentoCollection;


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
}
