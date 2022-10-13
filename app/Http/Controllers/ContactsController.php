<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Http\Resources\UserEquipaCollection;
use App\Http\Resources\NotificationCollection;
use App\Events\CreateContactEvent;
use App\Models\Contact;
use App\Notifications\NewContactNotification;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ContactsController extends Controller
{
    public function index()
    {
        return Inertia::render('Contacts/Index', [
            'filters' => Request::all('search', 'trashed'),
            'contacts' => new ContactCollection(
                Auth::user()->account->contacts()
                    ->orderBy('id', 'desc')
                    ->filter(Request::only('search', 'trashed'))
                    ->paginate()
                    ->appends(Request::all())
            ),
            'quantidade' => Contact::count(),
        ]);
    }

    public function create()
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            return Inertia::render('Contacts/Create', [
                'equipas' => new UserEquipaCollection(
                    Auth::user()->account->equipas()
                        ->orderBy('id')
                        ->get()
                ),
            ]);
        } 
    }


    public function store(ContactStoreRequest $request)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            Auth::user()->account->contacts()->create(
                $request->validated()
            );
            Log::channel('daily')->info('Parceiro <<' . $request->first_name . ' ' . $request->first_name . '>> ' . $request->imei . ' criado' . ' pela equipa: ' . $request->codigo_equipa 
            ,[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
            // $contact = Contact::where('imei', $request->imei)->first();
            // CreateContactEvent::dispatch($contact);
            return Redirect::route('contacts')->with('success', 'Parceiro <<' . $request->first_name . ' ' . $request->first_name . '>> ' . $request->imei . ' criado.');
        }
    }

    public function edit($id, $type, $read_contact)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            if($read_contact == "0"):
                DB::table('contacts')
                ->where('contacts.id', Crypt::decryptString($id))
                ->update(['contacts.read_contact' => $type]);
                Log::channel('daily')->info('Parceiro <<' . Crypt::decryptString($id) . '>> lido.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
            endif;
            return Inertia::render('Contacts/Edit', [
                'contact' => new ContactResource(Contact::withTrashed()->findOrFail(Crypt::decryptString($id))),
            ]);
        }
    }

    public function update(Contact $contact, ContactUpdateRequest $request)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $contact->update(
                $request->validated()
            );
            Log::channel('daily')->info('Parceiro <<' . $request->first_name . ' ' . $request->first_name . '>> ' . $request->imei . ' actualizado.' 
            ,[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);            
            return Redirect::back()->with('success', 'Parceiro actualizado.');
        }
    }

    public function destroy($id, $motivo)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $contact = Contact::findOrFail($id);
            $contact->motivo_elimina = $motivo;
            $contact->save();
            $contact->delete();
            Log::channel('daily')->alert('Parceiro <<' . $contact->imei . '>> eliminado.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
            return Redirect::back()->with('success', 'Parceiro eliminado.');
        }
    }

    public function restore(Contact $contact)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $contact->motivo_elimina = null;
            $contact->restore();
            $contact->save();
            Log::channel('daily')->alert('Parceiro <<' . $contact->imei . '>> lido.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
            return Redirect::back()->with('success', 'Parceiro restaurado.');
        }
    }

    public function estadoUpdate($id){
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $c = DB::table('contacts')
            ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
            ->where('contacts.id', Crypt::decryptString($id))
            ->latest('pagamentos.id')
            ->select('contacts.first_name', 'contacts.last_name', 'contacts.estado', 'pagamentos.fim')
            ->limit(1)
            ->get();

            if(!empty($c['0'])){
                $nome_parceiro = $c['0']->first_name .' '. $c['0']->last_name;
                if($c['0']->estado == '0' && $c['0']->fim <= date('Y-m-d')) {
                    Log::channel('daily')->alert('Tentou activar o parceiro <<' . Crypt::decryptString($id) . ' - ' . $nome_parceiro . '>>.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
                    return Redirect::route('contacts')->with('error', $nome_parceiro . ' com pagamento terminado 😢');
                } elseif ($c['0']->estado == '1' && $c['0']->fim > date('Y-m-d')) {
                    Log::channel('daily')->alert('Tentou desactivar o parceiro <<' . Crypt::decryptString($id) . ' - ' . $nome_parceiro . '>>.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
                    return Redirect::route('contacts')->with('error', $nome_parceiro . ' com pagamento não terminado 😊');
                } else {
                    DB::table('contacts')
                    ->where('contacts.id', Crypt::decryptString($id))
                    ->update(['contacts.estado' => $c['0']->estado == '0' ? '1' : '0']);
                    Log::channel('daily')->emergency('Parceiro <<' . Crypt::decryptString($id) . ' - ' . ($c['0']->estado == '0' ? $nome_parceiro . ' Activado' : $nome_parceiro . ' Desactivado') . '>>.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
                    return Redirect::route('contacts')->with('success', $c['0']->estado == '0' ? $nome_parceiro . ' Activado 😊' : $nome_parceiro . ' Desactivado 😢');
                }
            } else {
                Log::channel('daily')->alert('Tentou activar o parceiro <<' . Crypt::decryptString($id) .'>>.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
                return Redirect::route('contacts')->with('error', 'Parceiro sem pagamento 😢');
            }
        }
    }

    public function refresh(){
        $affected = DB::table('contacts')
        ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
        ->where('pagamentos.fim', '<=', date('Y-m-d'))
        ->where('contacts.estado', '1')
        ->latest('pagamentos.id')
        ->update(['contacts.estado' => '0']);
        Log::channel('daily')->alert('Estado de pagamento de parceiro verificado.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
        if($affected == '0'){
            return Redirect::route('contacts')->with('success', 'Nenhum Parceiro desactivado (sem pagamentos terminados)');
        }else{
            return Redirect::route('contacts')->with('success', $affected . ' Parceiro(s) desactivado(s) - (com pagamento terminado)');
        }
    }

    public function indexContactNotification($type)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            if($type == "0"):
                return $this->getNotificationLer($type);
            elseif($type == "1"):
                return $this->getNotificationLer($type);
            elseif($type == "2"):
                return $this->getNotificationEstado('1');
            elseif($type == "3"):
                return $this->getNotificationEstado('0');
            else :
                return Inertia::render('Notifications/Index', [
                    'contacts' => new NotificationCollection(
                        Auth::user()->account->contacts()
                            ->orderBy('id', 'desc')
                            ->paginate()
                            ->appends(Request::all())
                    ),
                    'quantidade' => Contact::count(),
                ]);
            endif;
        } else {
            return Inertia::render('Notifications/Index', [
                'contacts' => new NotificationCollection(
                    Auth::user()->account->contacts()
                        ->orderBy('id', 'desc')
                        ->paginate()
                        ->appends(Request::all())
                ),
                'quantidade' => Contact::count(),
            ]);
        }
    }

    function getNotificationEstado($estado){
        return Inertia::render('Notifications/Index', [
            'contacts' => new NotificationCollection(
                Auth::user()->account->contacts()
                    ->where('estado', $estado)
                    ->orderBy('id', 'desc')
                    ->paginate()
                    ->appends(Request::all())
            ),
            'quantidade' => Contact::where('estado', $estado)->count(),
        ]);
    }
    function getNotificationLer($type){
        return Inertia::render('Notifications/Index', [
            'contacts' => new NotificationCollection(
                Auth::user()->account->contacts()
                    ->where('read_contact', $type)
                    ->orderBy('id', 'desc')
                    ->paginate()
                    ->appends(Request::all())
            ),
            'quantidade' => Contact::where('read_contact', $type)->count(),
        ]);
    }

    function marcarNotificacao($id, $type, $local, $name){
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            if($type == "0"):
                return $this->marcarLer($id, $type, $local, $name);
            elseif($type == "1"):
                return $this->marcarLer($id, $type, $local, $name);
            elseif($type == "2"):
                return $this->marcarEstado($id, '1', $local, $name);
            elseif($type == "3"):
                return $this->marcarEstado($id, '0', $local, $name);
            else :
            endif;
        }
    }

    function marcarEstado($id, $type, $local, $name){
        DB::table('contacts')
        ->where('contacts.id', Crypt::decryptString($id))
        ->update(['contacts.estado' => $type]);
        return Redirect::route('contacts.notification', $local)->with('success', $type == '0' ? $name . ' marcada como não atendida 🔔' : $name . ' marcada como atendida 🔔');
    }
    function marcarLer($id, $type, $local, $name){
        DB::table('contacts')
        ->where('contacts.id', Crypt::decryptString($id))
        ->update(['contacts.read_contact' => $type]);
        return Redirect::route('contacts.notification', $local)->with('success', $type == '0' ? $name . ' marcada como não lida 🔔' : $name . ' marcada como lida 🔔');
    }

    function indexContactsFirebase() {
        return Inertia::render('ContactFirebase/Index');
    }
}
