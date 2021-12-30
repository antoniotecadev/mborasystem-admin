<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Resources\ContactResource;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    public function index($imei){
        return DB::table('contacts')
        ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
        ->where('imei', $imei)
        ->orderBy('pagamentos.id', 'desc')
        ->limit(1)
        ->get();
}

    public function store(Request $request)
    {
       try {

            $c = new Contact();

            $c->account_id = $request->account_id;
            $c->first_name = $request->first_name;
            $c->last_name = $request->last_name;
            $c->nif_bi = $request->nif_bi;
            $c->email = $request->email;
            $c->phone = $request->phone;
            $c->alternative_phone = $request->alternative_phone;
            $c->cantina = $request->cantina;
            $c->municipality = $request->municipality;
            $c->district = $request->district;
            $c->street = $request->street;
            $c->imei = $request->imei;

            $c->save();

            return ['insert' => 'ok'];

       } catch (\Throwable $th) {

            return ['insert' => 'erro'];

       }
    }

    // public function update(Request $request, $id)
    // {
    //     try {

    //         $c = Contact::findOrFail($id);

    //         $c->account_id = $request->account_id;
    //         $c->first_name = $request->first_name;
    //         $c->last_name = $request->last_name;
    //         $c->nif_bi = $request->nif_bi;
    //         $c->email = $request->email;
    //         $c->phone = $request->phone;
    //         $c->alternative_phone = $request->alternative_phone;
    //         $c->cantina = $request->cantina;
    //         $c->municipality = $request->municipality;
    //         $c->district = $request->district;
    //         $c->street = $request->street;

    //         $c->save();

    //         return ['update' => 'ok'];

    //    } catch (\Throwable $th) {

    //         return ['update' => 'erro'];

    //    }
    // }

}
