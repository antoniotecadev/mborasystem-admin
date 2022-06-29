<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Events\CreateContactEvent;
use App\Http\Resources\ContactResource;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;


class ContactsController extends Controller
{
    public function index($imei){

        $imeiLength = Str::length($imei);

        if($imeiLength > 10 and $imeiLength < 20):
            $c = DB::table('contacts')
            ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
            ->where('imei', $imei)
            ->latest('pagamentos.id')
            ->select('contacts.first_name', 'contacts.last_name', 'contacts.nif_bi', 'contacts.email', 'contacts.phone', 'contacts.alternative_phone', 'contacts.cantina', 'contacts.municipality', 'contacts.district', 'contacts.street', 'contacts.estado', 'contacts.imei', 'pagamentos.pacote', 'pagamentos.inicio', 'pagamentos.fim')
            ->limit(1)
            ->get();
        else:
            return $this->dataEmpty();
        endif;

        // $dataFim = new DateTime($collection['0']->inicio);
        // $dataFim->add(new DateInterval('P30D'));
        // $dataFim->format('Y-m-d');

        if(empty($c['0'])) {
            return $this->dataEmpty();
        } else {
            if($c['0']->fim <= date('Y-m-d')) {
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
            'termina' => $termina,
            'contactos' => "\nCALL: 222 727 519 | 937 115 891\nEMAIL: yoga.apoio.tecnico@gmail.com\nWHATSAPP: +244 937 115 891"
             ]];
        }
}

    private function dataEmpty(){
        return [[ 'first_name' => '',
            'last_name' => '',
            'nif_bi' => '',
            'email' => '',
            'phone' => '',
            'alternative_phone' => '',
            'cantina' => '',
            'municipality' => '',
            'district' => '',
            'street' => '',
            'estado' => 0,
            'imei' => '',
            'pacote' => 3,
            'inicio' => '',
            'fim' => '',
            'termina' => '1',
            'contactos' => "\nCALL: 222 727 519 | 937 115 891\nEMAIL: yoga.apoio.tecnico@gmail.com\nWHATSAPP: +244 937 115 891"
             ]];
    }

    public function store(Request $request)
    {

        $c = new Contact();

        try {

            if ($request->has(['codigo_equipa', 'first_name', 'last_name', 'nif_bi', 'email', 'phone', 'alternative_phone', 'cantina', 'municipality', 'district', 'street', 'imei'])) {

            $c->account_id = $request->account_id;
            $c->codigo_equipa = $request->codigo_equipa;
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
            Log::channel('daily')->info('MBORASYSTEM CRIADO:  Parceiro <<' . $request->first_name . ' ' . $request->last_name . ' - ' . $request->imei . '>> criado pela equipa <<' . $request->codigo_equipa . '>>.');
            return ['insert' => 'ok'];
            } else {
                return ['insert' => 'erro'];
            }

            // $contact = Contact::where('imei', $request->imei)->first();
            // CreateContactEvent::dispatch($contact);

       } catch (\Throwable $th) {
            Log::channel('daily')->emergency('MBORASYSTEM ERRO AO CRIAR:  Parceiro <<' . $request->first_name . ' ' . $request->last_name . ' - ' . $request->imei . '>> Equipa <<' . $request->codigo_equipa . '>>.');
            return ['insert' => 'erro'];
       }
    }

    public function getContactos()
    {
        return [[ 'contactos' => "\nCALL: 222 727 519 | 937 115 891\nEMAIL: yoga.apoio.tecnico@gmail.com\nWHATSAPP: +244 937 115 891" ]];
    }

    public function getBairros($municipio)
    {
        $bairros = [ 
            'Belas' => [
                ['br' => 'Barra do Kwanza'], 
                ['br' => 'Cabolombo'], 
                ['br' => 'Ilha da Cazanga'],
                ['br' => 'Ilha dos Pássaros'],
                ['br' => 'Kilamba'],
                ['br' => 'KK5000'],
                ['br' => 'Morro dos Veados'],
                ['br' => 'Quenguela'],
                ['br' => 'Ramiros'],
                ['br' => 'Vila Verde 1'],
                ['br' => 'Vila Verde 2']
            ],
            'Cacuaco' => [
                ['br' => 'Bairro de Chapa - Via Espressa'],
                ['br' => 'Bairro Vidrul Luanda'],
                ['br' => 'Balumuka'],
                ['br' => 'Boa Esperança'],
                ['br' => 'Cacuaco'],
                ['br' => 'Cimangola'],
                ['br' => 'Funda'], 
                ['br' => 'Kifangondo'], 
                ['br' => 'Kikolo'], 
                ['br' => 'Mulenvos Baixos'],
                ['br' => 'Muluéka'],
                ['br' => 'Panguila'],
                ['br' => 'Paraíso'],
                ['br' => 'Kilamba'],
                ['br' => 'Sequele'],
                ['br' => 'Centralidade do Cacuaco']
            ],
            'Cazenga' => [
                ['br' => '11 de Novembro'], 
                ['br' => 'Asa Branca'], 
                ['br' => 'Cala Boca'], 
                ['br' => 'Cuca'], 
                ['br' => 'Curtume'], 
                ['br' => 'Bairro Socola'], 
                ['br' => 'Calawenda'],
                ['br' => 'Cazenga'],
                ['br' => 'Filda'],
                ['br' => 'Hoji Ya Henda'], 
                ['br' => 'Kalawenda'],
                ['br' => 'Kima Kieza'],
                ['br' => 'Levilagi'],
                ['br' => 'Mabor'],
                ['br' => 'Nocal'],
                ['br' => 'Ser Madó'], 
                ['br' => 'Sonefe'],
                ['br' => 'Tala Hadi']
            ],
            'IcoloeBengo' => [
                ['br' => 'Bela Vista'],
                ['br' => 'Bom Jesus'], 
                ['br' => 'Cabiri'], 
                ['br' => 'Caculo'],
                ['br' => 'Caculo Cahango'],
                ['br' => 'Cahango'],
                ['br' => 'Cassoneca'],
                ['br' => 'Catete'],
                ['br' => 'Quiminha']
            ],
            'Quissama' => [
                ['br' => 'Cabo Ledo'],
                ['br' => 'Chio'], 
                ['br' => 'Demba'], 
                ['br' => 'Demba Chio'],
                ['br' => 'Mumbondo'],
                ['br' => 'Muxima'],
                ['br' => 'Quixinge']
            ],
            'Talatona' => [
                ['br' => '11 de Novembro'], 
                ['br' => 'Benfica'],
                ['br' => 'Calemba'], 
                ['br' => 'Calemba 2'], 
                ['br' => 'Camama'],
                ['br' => 'Cidade Universitária'],
                ['br' => 'Fubú'],
                ['br' => 'Futungo de Belas'],
                ['br' => 'Ilha do Mussulo'],
                ['br' => 'Lar do Patriota'],
                ['br' => 'Talatona']
            ],
            'KilambaKiaxi' => [
                ['br' => 'Bairro Popular'], 
                ['br' => 'Capolo 2'],
                ['br' => 'Cassequel'],
                ['br' => 'Cassequel do Buraco'],
                ['br' => 'Cassequel do João Lourenço'],
                ['br' => 'Golf'], 
                ['br' => 'Golf 2'], 
                ['br' => 'Nova Vida'],
                ['br' => 'Palanca'],
                ['br' => 'Sapú'],
                ['br' => 'Teixera de Luanda'],
                ['br' => 'Vila Estoril']
            ],
            'Viana' => [
                ['br' => 'Baia'], 
                ['br' => 'Bairro da BCA Luanda'], 
                ['br' => 'Bairro do Huambo'], 
                ['br' => 'Bairro dos Bois'], 
                ['br' => 'Bairro Robaldina Luanda'], 
                ['br' => 'Calumbo'], 
                ['br' => 'Centralidade do Zango'], 
                ['br' => 'Estalagem'],
                ['br' => 'Gamek de Luanda'],
                ['br' => 'Grafanil de Luanda'],
                ['br' => 'Kikuxi'], 
                ['br' => 'Kilómetro 30'], 
                ['br' => 'Luanda Sul'], 
                ['br' => 'Sapu'], 
                ['br' => 'Vila da Mata'],
                ['br' => 'Vila de Viana'],
                ['br' => 'Vila Flor'],
                ['br' => 'Zango'],
                ['br' => 'Zango 1'],
                ['br' => 'Zango 2'],
                ['br' => 'Zango 3'],
                ['br' => 'Zango 4'],
                ['br' => 'Zango 5']
            ],
            'Luanda' => [
                ['br' => '1 de Maio'],
                ['br' => 'Alvalade'],
                ['br' => 'Cruzeiro'],
                ['br' => 'Bairro Azul'],
                ['br' => 'Bairro da Dona Malha'],
                ['br' => "Bairro das B'S e das C'S - Nelito Soares"],
                ['br' => "Bairro dos CTT'S Luanda"],
                ['br' => 'Bairro dos Kwanzas'],
                ['br' => 'Bairro Operário Luanda'],
                ['br' => 'Bairro Popular de Luanda'],
                ['br' => 'Bairro Uíge'],
                ['br' => 'Baleizão'],
                ['br' => 'Baía de Luanda'],
                ['br' => 'Baixa de Luanda'],
                ['br' => 'Boa Vista'],
                ['br' => 'Cassenda'],
                ['br' => 'Catambor'],
                ['br' => 'Campismo Luanda'],
                ['br' => 'Chaba'],
                ['br' => 'Cidade de Luanda'],
                ['br' => 'Codeme'],
                ['br' => 'Comandante Valódia - Combatentes'],
                ['br' => 'Complexo'],
                ['br' => 'Coqueiros'],
                ['br' => 'Coreia'],
                ['br' => 'Chicala 1'], 
                ['br' => 'Chicala 2'], 
                ['br' => 'Corimba'], 
                ['br' => 'EMCIB'],
                ['br' => 'Gamek'], 
                ['br' => 'Gamek a Direita'], 
                ['br' => 'Ilha de Luanda'], 
                ['br' => 'Ingombota'], 
                ['br' => 'Katinton'], 
                ['br' => 'Kinaxixi'], 
                ['br' => 'Maculusso'], 
                ['br' => 'Maianga'], 
                ['br' => 'Márteres do Kifangondo'],
                ['br' => 'Miramar'],
                ['br' => 'Marçal'],
                ['br' => 'Marçal de Luanda'],
                ['br' => 'Marginal de Luanda'],
                ['br' => 'Morro Bento'], 
                ['br' => 'Morro Bento 2'], 
                ['br' => 'Morro da Luz'], 
                ['br' => 'Mutamba'], 
                ['br' => 'Neves Bendinha'],
                ['br' => 'Ngola Kiluanje'], 
                ['br' => 'Nguanhã'],
                ['br' => 'Petrangol'],
                ['br' => 'Petrofe'],
                ['br' => 'Porto Pesqueiro da Boa Vista'],
                ['br' => 'Precol'],
                ['br' => 'Prenda'],
                ['br' => 'Rangel'], 
                ['br' => 'Rocha Padaria'], 
                ['br' => 'Rocha Pinto'], 
                ['br' => 'Sagrada Esperança'],
                ['br' => 'Samba'],
                ['br' => 'Sambizanga'],
                ['br' => 'São Paulo'],
                ['br' => 'São Pedro da Barra'],
                ['br' => 'Sambizanga'],
                ['br' => 'Tunga Ngó'],
                ['br' => 'Vila Alice'], 
                ['br' => 'Vila do Gamek'],
                ['br' => 'Zamba 2']
            ]
    ];

    	return Arr::has($bairros, $municipio) ? $bairros[$municipio] : [['br' => '']];
    }

}
