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
        return Inertia::render('Contacts/Create', [
            'equipas' => new UserEquipaCollection(
                Auth::user()->account->equipas()
                    ->orderBy('id')
                    ->get()
            ),
        ]);
    }


    public function store(ContactStoreRequest $request)
    {
        Auth::user()->account->contacts()->create(
            $request->validated()
        );

        $contact = Contact::where('imei', $request->imei)->first();
        CreateContactEvent::dispatch($contact);
        return Redirect::route('contacts')->with('success', 'Parceiro criado.');
    }

    public function edit($id, $type, $read_contact)
    {

        if($read_contact == "0"):
            DB::table('contacts')
            ->where('contacts.id', Crypt::decryptString($id))
            ->update(['contacts.read_contact' => $type]);
        endif;

        return Inertia::render('Contacts/Edit', [
            'contact' => new ContactResource(Contact::withTrashed()->findOrFail(Crypt::decryptString($id))),
        ]);
    }

    public function update(Contact $contact, ContactUpdateRequest $request)
    {
        $contact->update(
            $request->validated()
        );

        return Redirect::back()->with('success', 'Parceiro actualizado.');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        $contact->delete();

        return Redirect::back()->with('success', 'Parceiro eliminado.');
    }

    public function restore(Contact $contact)
    {
        $contact->restore();

        return Redirect::back()->with('success', 'Parceiro restaurado.');
    }

    public function estadoUpdate($id){

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
                return Redirect::route('contacts')->with('error', $nome_parceiro . ' com pagamento terminado 😢');
            } elseif ($c['0']->estado == '1' && $c['0']->fim > date('Y-m-d')) {
                return Redirect::route('contacts')->with('error', $nome_parceiro . ' com pagamento não terminado 😊');
            } else {
                DB::table('contacts')
                ->where('contacts.id', Crypt::decryptString($id))
                ->update(['contacts.estado' => $c['0']->estado == '0' ? '1' : '0']);
                return Redirect::route('contacts')->with('success', $c['0']->estado == '0' ? $nome_parceiro . ' Activado 😊' : $nome_parceiro . ' Desactivado 😢');
            }
        } else {
            return Redirect::route('contacts')->with('error', 'Parceiro sem pagamento 😢');
        }
    }

    public function refresh(){

        $affected = DB::table('contacts')
        ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
        ->where('pagamentos.fim', '<=', date('Y-m-d'))
        ->where('contacts.estado', '1')
        ->latest('pagamentos.id')
        ->update(['contacts.estado' => '0']);
        if($affected == '0'){
            return Redirect::route('contacts')->with('success', 'Nenhum parceiro desactivado (sem pagamentos terminados)');
        }else{
            return Redirect::route('contacts')->with('success', $affected . ' parceiro(s) desactivado(s) - (com pagamento terminado)');
        }
    }

    public function indexContactNotification($type)
    {
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
}
