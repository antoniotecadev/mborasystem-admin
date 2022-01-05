<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Resources\ContactResource;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    public function index($imei){
        
        $c = DB::table('contacts')
        ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
        ->where('imei', $imei)
        ->latest('pagamentos.id')
        ->select('contacts.first_name', 'contacts.last_name', 'contacts.nif_bi', 'contacts.email', 'contacts.phone', 'contacts.alternative_phone', 'contacts.cantina', 'contacts.municipality', 'contacts.district', 'contacts.street', 'contacts.estado', 'contacts.imei', 'pagamentos.pacote', 'pagamentos.inicio', 'pagamentos.fim')
        ->limit(1)
        ->get();
        
        // $dataFim = new DateTime($collection['0']->inicio);
        // $dataFim->add(new DateInterval('P30D'));
        // $dataFim->format('Y-m-d');

        if($c['0']->fim <= date('Y-m-d') || $c['0']->estado == 0) {
            $termina = 1;
        } else {
            $termina = 0;
        }
        
        return [[ 'first_name' => $c['0']->first_name,
         'last_name' => $c['0']->last_name, 
         'nif_bi' => $c['0']->nif_bi, 
         'email' => $c['0']->email, 
         'phone' => $c['0']->phone, 
         'alternative_phone' => $c['0']->alternative_phone, 
         'cantina' => $c['0']->cantina, 
         'municipality' => $c['0']->municipality, 
         'district' => $c['0']->district, 
         'street' => $c['0']->street, 
         'estado' => $c['0']->estado, 
         'imei' => $c['0']->imei, 
         'pacote' => $c['0']->pacote, 
         'inicio' => $c['0']->inicio, 
         'fim' => $c['0']->fim, 
         'termina' => $termina ]];

        // echo "<pre>";
        // print_r($collapsed->all());
        // echo "</pre>";
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

}
