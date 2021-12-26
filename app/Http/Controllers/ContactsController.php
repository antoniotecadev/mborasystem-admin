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

class ContactsController extends Controller
{
    public function index()
    {
        return Inertia::render('Contacts/Index', [
            'filters' => Request::all('search', 'trashed'),
            'contacts' => new ContactCollection(
                Auth::user()->account->contacts()
                    // ->orderByName()
                    ->orderBy('id')
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
            'contact' => new ContactResource(Contact::findOrFail(Crypt::decryptString($id))),
        ]);
    }

    public function update(Contact $contact, ContactUpdateRequest $request)
    {
        $contact->update(
            $request->validated()
        );

        return Redirect::back()->with('success', 'Parceiro actualizado.');
    }

    public function destroy(Contact $contact)
    {
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
        return $id;
    }

}
