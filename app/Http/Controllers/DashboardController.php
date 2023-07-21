<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;
use App\Models\Equipa;
use App\Models\Pagamento;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard/Index',[
            'empresa' => [ 
                'total' => Contact::count(), 
                'activas' => Contact::where('estado', '1')->count(), 
                'desactivas' => Contact::where('estado', '0')->count(),
                'eliminadas' => Contact::onlyTrashed()->count(),
            ],
            'equipa' => [ 
                'total' => Equipa::count(), 
                'activas' => Equipa::where('estado', '1')->count(), 
                'desactivas' => Equipa::where('estado', '0')->count(),
                'eliminadas' => Equipa::onlyTrashed()->count(),
            ],
            'pagamento' => [ 
                'total' => Pagamento::count(), 
                'activas' => Pagamento::where('pagamento', '1')->count(), 
                'desactivas' => Pagamento::where('pagamento', '0')->count(),
                'eliminadas' => Pagamento::onlyTrashed()->count(),
            ],
        ]);
    }
}
