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
            'parceiro' => [ 
                'total' => Contact::count(), 
                'activos' => Contact::where('estado', '1')->count(), 
                'desactivos' => Contact::where('estado', '0')->count(),
                'eliminados' => Contact::onlyTrashed()->count(),
            ],
            'equipa' => [ 
                'total' => Equipa::count(), 
                'activos' => Equipa::where('estado', '1')->count(), 
                'desactivos' => Equipa::where('estado', '0')->count(),
                'eliminados' => Equipa::onlyTrashed()->count(),
            ],
            'pagamento' => [ 
                'total' => Pagamento::count(), 
                'activos' => Pagamento::where('pagamento', '1')->count(), 
                'desactivos' => Pagamento::where('pagamento', '0')->count(),
                'eliminados' => Pagamento::onlyTrashed()->count(),
            ],
            'municipios_parceiros_desactivos' => $this->getMunicipios('0'),
            'municipios_parceiros_activos' => $this->getMunicipios('1'),
        ]);
    }

    private function getMunicipios($estado)
    {
        return DB::table('municipios', 'm')
            ->join('contacts as c', 'm.nome', '=', 'c.municipality')
            ->select('c.id')
            ->select(DB::raw('count(*) as numero_activo, c.municipality'))
            ->groupBy('c.municipality') 
            ->where('c.estado', $estado)
            ->get();   
    }
}
