<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipaStoreRequest;
use App\Http\Requests\EquipaUpdateRequest;
use App\Http\Resources\EquipaCollection;
use App\Http\Resources\equipaResource;
use App\Models\Equipa;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EquipasController extends Controller
{
    public function index()
    {
        return Inertia::render('Equipas/Index', [
            'filters' => Request::all('search', 'trashed'),
            'equipas' => new EquipaCollection(
                Auth::user()->account->equipas()
                    ->orderBy('id', 'desc')
                    ->filter(Request::only('search', 'trashed'))
                    ->paginate()
                    ->appends(Request::all())
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Equipas/Create');
    }


    public function store(EquipaStoreRequest $request)
    {
        Auth::user()->account->equipas()->create(
            $request->validated()
        );

        return Redirect::route('equipas')->with('success', 'Equipa criada.');
    }

    public function edit($id)
    {
        return Inertia::render('Equipas/Edit', [
            'equipa' => new EquipaResource(Equipa::withTrashed()->findOrFail(Crypt::decryptString($id))),
        ]);
    }

    public function update(Equipa $equipa, EquipaUpdateRequest $request)
    {
        $equipa->update(
            $request->validated()
        );

        return Redirect::back()->with('success', 'Equipa actualizada.');
    }

    public function destroy($id)
    {
        $equipa = Equipa::findOrFail($id);

        $equipa->delete();

        return Redirect::back()->with('success', 'Equipa eliminada.');
    }

    public function restore(Equipa $equipa)
    {
        $equipa->restore();

        return Redirect::back()->with('success', 'Equipa restaurada.');
    }

    public function estadoUpdate($id){
        $c = Equipa::findOrFail(Crypt::decryptString($id));
        $c->estado = $c->estado == '0' ? '1' : '0' ;
        $c->save();
        return Redirect::route('equipas')->with('success', 'Confirmado');
    }
}
