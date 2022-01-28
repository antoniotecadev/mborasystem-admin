<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
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
        ]);
    }

    public function create()
    {
        return Inertia::render('Contacts/Create');
    }


    public function store(ContactStoreRequest $request)
    {
        Auth::user()->account->contacts()->create(
            $request->validated()
        );

        return Redirect::route('contacts')->with('success', 'Parceiro criado.');
    }

    public function edit($id)
    {
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
        $c = Contact::findOrFail(Crypt::decryptString($id));
        $c->estado = $c->estado == '0' ? '1' : '0' ;
        $c->save();
        return Redirect::route('contacts')->with('success', 'Confirmado');
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
}
