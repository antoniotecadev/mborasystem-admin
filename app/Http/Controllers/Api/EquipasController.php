<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EquipaCollection;
use App\Http\Resources\EquipaResource;
use App\Models\Contact;
use App\Models\Equipa;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use PHPUnit\Framework\Constraint\IsEmpty;

use function PHPUnit\Framework\isEmpty;

class EquipasController extends Controller
{

    public function verificarCodigo($codigo){

        $codigoLength = Str::length($codigo);

        if($codigoLength == 6):
            $c = DB::table('equipas')
            ->join('agentes', 'agentes.equipa_id', '=', 'equipas.id')
            ->where('codigo', $codigo)
            ->limit(1)
            ->select('equipas.codigo', 'equipas.estado')
            ->get();
        else:
            return [[ 'codigo' => '', 'estado' => 0]];
        endif;

        if(empty($c['0'])) {
            return [[ 'codigo' => '', 'estado' => 0]];
        } else {
            return $c;
        }
    }

    public function getListaEquipas()
    {
        
        return Inertia::render('Equipas/Lista', [
            'filters' => Request::all('search', 'trashed'),
            'equipas' => new EquipaCollection(
                Equipa::orderBy('id', 'desc')
                ->filter(Request::only('search', 'trashed'))
                ->paginate()
                ->appends(Request::all())
            ),
            'quantidade' => Equipa::count(),
        ]);
    }

    public function rendimentoEquipas($id, $codigo)
    {
        return Inertia::render('Equipas/Login', [
            'id' => $id,
            'codigo' => $codigo,
        ]);
    }

    public function loginEquipa(\Illuminate\Http\Request $request, $id, $codigo, $palavra_passe)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|size:6',
            'password' => 'required|alpha_num',
        ]);
        if ($validator->fails()):
            $messages = $validator->messages();
            $errors = $messages->all();
            return $this->LoginPage($id, $codigo, $errors[0]);
        else :
            $password = Equipa::where('codigo', $codigo)->limit(1)->get('password');
            if($password === []):
                return $this->LoginPage($id, $codigo, 'Equipa YOGA não encontrada');
            elseif (Hash::check($palavra_passe, $password['0'] ->password)):
                return Inertia::render('Equipas/Rendimento', [
                    'equipa' => new EquipaResource(Equipa::withTrashed()->findOrFail(Crypt::decryptString($id))),
                ]);
            else :
                return $this->LoginPage($id, $codigo, 'Equipa YOGA não encontrada');
            endif;
         endif;
    }

    private function LoginPage($id, $codigo, $errors){
        return Inertia::render('Equipas/Login', [
            'id' => $id,
            'codigo' => $codigo,
            'error' => $errors,
        ]);
    }

    public function calcularRendimentoEquipa($id, $codigo, $inicio, $fim, $numeroagente, $percentagemTaxa){
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

            return Inertia::render('Equipas/Rendimento', [
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
