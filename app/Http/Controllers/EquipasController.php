<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipaStoreRequest;
use App\Http\Requests\EquipaUpdateRequest;
use App\Http\Resources\EquipaCollection;
use App\Http\Resources\equipaResource;
use App\Models\Equipa;
use App\Models\Contact;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
            'quantidade' => Equipa::count(),
        ]);
    }

    public function create()
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            return Inertia::render('Equipas/Create');
        }
    }


    public function store(EquipaStoreRequest $request)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            Auth::user()->account->equipas()->create(
                $request->validated()
            );

            return Redirect::route('equipas')->with('success', 'Equipa criada.');
        }
    }

    public function edit($id)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            return Inertia::render('Equipas/Edit', [
                'equipa' => new EquipaResource(Equipa::withTrashed()->findOrFail(Crypt::decryptString($id))),
            ]);
        }
    }

    public function update(Equipa $equipa, EquipaUpdateRequest $request)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $equipa->update(
                $request->validated()
            );
            return Redirect::back()->with('success', 'Equipa actualizada.');
        }
    }

    public function updatePassword(Equipa $equipa)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $equipa->update(
                Request::validate([
                    'password' => ['required', 'min:8', 'max:15', 'alpha_num']
                ])
            );
            return Redirect::back()->with('success', 'Palavra passe alterada.');
        }
    }

    public function destroy($id, $motivo)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $equipa = Equipa::findOrFail($id);
            $equipa->motivo_elimina = $motivo;
            $equipa->save();
            $equipa->delete();
            return Redirect::back()->with('success', 'Equipa eliminada.');
        }
    }

    public function restore(Equipa $equipa)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $equipa->motivo_elimina = null;
            $equipa->restore();
            $equipa->save();
            return Redirect::back()->with('success', 'Equipa restaurada.');
        }
    }

    public function estadoUpdate($id){
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $c = Equipa::findOrFail(Crypt::decryptString($id));
            $c->estado = $c->estado == '0' ? '1' : '0' ;
            $c->save();
            return Redirect::route('equipas')->with('success', 'Confirmado');
        }
    }

    public function calcularLucroEquipa($id, $codigo, $inicio, $fim, $numeroagente, $percentagemTaxa){
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $c = Contact::where('contacts.codigo_equipa', $codigo)
            ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
            ->whereBetween('contacts.created_at', [$inicio, $fim])
            ->where('pagamentos.pagamento', '1')
            ->orderBy('contacts.id', 'desc')
            ->get(['contacts.id as idcontact', 'contacts.first_name', 'contacts.last_name', 'contacts.imei', 'contacts.read_contact', 'contacts.created_at as datacriacontact', 'pagamentos.pacote', 'pagamentos.preco', 'pagamentos.created_at as datacriapagamento']);

            $r = 0;
            $contact = [];
            foreach($c as $p){
                $r += $p->preco;
                $contact[] = ["idcontact" => Crypt::encryptString($p->idcontact), "first_name" => $p->first_name, "last_name" => $p->last_name, "imei" => $p->imei, "read_contact" => $p->read_contact, "datacriacontact" => $p->datacriacontact, "pacote" => $p->pacote, "preco" => $p->preco, "datacriapagamento" => $p->datacriapagamento];
            }

            // return dd($contact);
            return Inertia::render('Equipas/Edit', [
                'equipa' => new EquipaResource(Equipa::withTrashed()->findOrFail($id)),
                'parceiros' => $contact,
                'valorcada' =>($r*($percentagemTaxa/100)) / $numeroagente,
                'valortotal' => ($r*($percentagemTaxa/100)),
                'valortotalbruto' => $r,
                'iniciodata' => $inicio,
                'fimdata' => $fim,
                'numeroagente' => $numeroagente,
                'percentagemtaxa' => $percentagemTaxa,
                'quantidade' => count($c),
            ]);
        }
    }
}
