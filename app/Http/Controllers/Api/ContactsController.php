<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContactsController extends Controller
{
    public function index($imei){

        $pm = new ProdutosMboraController();

        $imeiLength = Str::length($imei);

        if($imeiLength > 10 and $imeiLength < 20):
            $c = DB::table('contacts')
            ->join('pagamentos', 'pagamentos.contact_id', '=', 'contacts.id')
            ->join('dispositivos as d', 'd.contact_id', '=', 'contacts.id')
            ->where('imei', $imei)
            ->latest('pagamentos.id')
            ->select('contacts.provincia_id', 'contacts.first_name', 'contacts.last_name', 'contacts.nif_bi', 'contacts.email', 'contacts.phone', 'contacts.alternative_phone', 'contacts.empresa', 'contacts.municipality', 'contacts.district', 'contacts.street', 'contacts.estado', 'contacts.imei', 'pagamentos.pacote', 'pagamentos.tipo_pagamento', 'pagamentos.inicio', 'pagamentos.fim',
            'd.fabricante', 'd.marca', 'd.produto', 'd.modelo', 'd.versao', 'd.api', 'd.device')
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
            return [[ 'provincia' => $this->getProvincia($c['0']->provincia_id),
            'first_name' => $c['0']->first_name,
            'last_name' => $c['0']->last_name,
            'nif_bi' => $c['0']->nif_bi,
            'email' => $c['0']->email,
            'phone' => $c['0']->phone,
            'alternative_phone' => $c['0']->alternative_phone,
            'empresa' => $c['0']->empresa,
            'municipality' => $c['0']->municipality,
            'district' => $c['0']->district,
            'street' => $c['0']->street,
            'estado' => $c['0']->estado,
            'imei' => $c['0']->imei,
            'pacote' => $c['0']->pacote,
            'tipo_pagamento' => $c['0']->tipo_pagamento,
            'quantidade_produto_pacote' => $this->getQuantidadeProdutoPacote($c['0']->pacote, $c['0']->tipo_pagamento),
            'quantidade_produto' => $pm->getQuantidade($imei),
            'inicio' => $c['0']->inicio,
            'fim' => $c['0']->fim,
            'termina' => $termina,
            'contactos' => "\nCALL: 222 727 519 | 937 115 891\nEMAIL: yoga.empresa.suporte@gmail.com\nWHATSAPP: +244 937 115 891",
            'device' => $c['0']->fabricante . $c['0']->marca . $c['0']->produto . $c['0']->modelo . $c['0']->versao . $c['0']->api . $c['0']->device
             ]];
        }
}

    private function dataEmpty(){
        return [[ 'provincia' => '',
            'first_name' => '',
            'last_name' => '',
            'nif_bi' => '',
            'email' => '',
            'phone' => '',
            'alternative_phone' => '',
            'empresa' => '',
            'municipality' => '',
            'district' => '',
            'street' => '',
            'estado' => 0,
            'imei' => '',
            'pacote' => 3,
            'tipo_pagamento' => 4,
            'quantidade_produto' => '0',
            'inicio' => '',
            'fim' => '',
            'termina' => '1',
            'contactos' => "\nCALL: 222 727 519 | 937 115 891\nEMAIL: yoga.empresa.suporte@gmail.com\nWHATSAPP: +244 937 115 891",
            'device' => ''
             ]];
    }

    public function store(Request $request)
    {
        $c = new Contact();

        try {

            if ($request->has(['codigo_equipa', 'first_name', 'last_name', 'nif_bi', 'email', 'phone', 'alternative_phone', 'empresa', 'municipality', 'provincia', 'district', 'street', 'imei'])) {

                $c->account_id = $request->account_id;
                $c->codigo_equipa = $request->codigo_equipa;
                $c->provincia_id = $this->getIdProvincia($request->provincia);
                $c->first_name = $request->first_name;
                $c->last_name = $request->last_name;
                $c->nif_bi = $request->nif_bi;
                $c->email = $request->email;
                $c->phone = $request->phone;
                $c->alternative_phone = $request->alternative_phone;
                $c->empresa = $request->empresa;
                $c->municipality = $request->municipality;
                $c->district = $request->district;
                $c->street = $request->street;
                $c->imei = $request->imei;
                $c->save();
                $this->storeDeviceDetail($request, $c->id);
                return ['insert' => 'ok'];
            } else {
                return ['insert' => 'erro', 'throwable' => 'parâmetro de empresa errado.'];
            }
            // $contact = Contact::where('imei', $request->imei)->first();
            // CreateContactEvent::dispatch($contact);
        } catch (\Throwable $th) {
            Log::channel('daily')->emergency('MBORASYSTEM ERRO AO CRIAR:  Empresa <<' . $request->empresa . ' - ' . $request->first_name . ' ' . $request->last_name . ' - ' . $request->imei . '>> Equipa <<' . $request->codigo_equipa . '>> Throwable: ' . $th->getMessage());
            return ['insert' => 'erro', 'throwable' => 'ocorreu um erro ao registar empresa.'];
        }
    }

    private function getIdProvincia($provincia){
        return DB::table('provincias')
        ->where('nome', $provincia)
        ->get('id')[0]->id;
    }

    private function getProvincia($id){
        return DB::table('provincias')
        ->where('id', $id)
        ->get('nome')[0]->nome;
    }

    private function storeDeviceDetail($request, $contact_id){
        $data = array('contact_id' => $contact_id , 'fabricante' => $request->fabricante, 'marca' => $request->marca, 'produto' => $request->produto, 'modelo' => $request->modelo, 'versao' => $request->versao, 'api' => $request->api, 'device' => $request->device);
        DB::table('dispositivos')->insert($data);
        Log::channel('daily')->info('MBORASYSTEM CRIADO:  Parceiro <<' . $request->first_name . ' ' . $request->last_name . ' - ' . $request->imei . '>> criado pela equipa <<' . $request->codigo_equipa . '>>.');
    }

    public function getContactos()
    {
        return [[ 'contactos' => "\nCALL: 222 727 519 | 937 115 891\nEMAIL: yoga.apoio.tecnico@gmail.com\nWHATSAPP: +244 937 115 891" ]];
    }

    public function getMunicipios($provincia){
        return DB::table('provincias', 'p')
               ->join('municipios as m', 'm.provincia_id', '=', 'p.id')
               ->where('p.nome', $provincia)
               ->get('m.nome as mc');
    }

    public function getBairros($municipio){
        return DB::table('municipios', 'm')
               ->join('bairros as b', 'b.municipio_id', '=', 'm.id')
               ->where('m.nome', $municipio)
               ->get('b.nome as br');
    }


    public function getQuantidadeProdutoPacote($pacote, $tipo) {
        $quantidade = [
            '0' => [
                '1' => '5',
                '3' => '10',
                '6' => '15',
                '12' => '20',
            ],
            '1' => [
                '1' => '25',
                '3' => '30',
                '6' => '35',
                '12' => '40',
            ],
            '2' => [
                '1' => '45',
                '3' => '50',
                '6' => '55',
                '12' => '60',
            ],
        ];

        $quantidade = $quantidade[$pacote];
        return $quantidade[$tipo];
    }

    // private function getBairrosExemplo($municipio) {
    //     $bairros = [ 
    //         'Belas' => [
    //             ['br' => 'Barra do Kwanza'], 
    //             ['br' => 'Cabolombo'], 
    //             ['br' => 'Ilha da Cazanga'],
    //             ['br' => 'Ilha dos Pássaros'],
    //             ['br' => 'Kilamba'],
    //             ['br' => 'KK5000'],
    //             ['br' => 'Morro dos Veados'],
    //             ['br' => 'Quenguela'],
    //             ['br' => 'Ramiros'],
    //             ['br' => 'Vila Verde 1'],
    //             ['br' => 'Vila Verde 2']
    //         ],
    //         'Cacuaco' => [
    //             ['br' => 'Bairro de Chapa - Via Espressa'],
    //             ['br' => 'Bairro Vidrul Luanda'],
    //             ['br' => 'Balumuka'],
    //             ['br' => 'Boa Esperança'],
    //             ['br' => 'Cacuaco'],
    //             ['br' => 'Cimangola'],
    //             ['br' => 'Funda'], 
    //             ['br' => 'Kifangondo'], 
    //             ['br' => 'Kikolo'], 
    //             ['br' => 'Mulenvos Baixos'],
    //             ['br' => 'Muluéka'],
    //             ['br' => 'Panguila'],
    //             ['br' => 'Paraíso'],
    //             ['br' => 'Kilamba'],
    //             ['br' => 'Sequele'],
    //             ['br' => 'Centralidade do Cacuaco']
    //         ],
    //         'Cazenga' => [
    //             ['br' => '11 de Novembro'], 
    //             ['br' => 'Asa Branca'], 
    //             ['br' => 'Cala Boca'], 
    //             ['br' => 'Cuca'], 
    //             ['br' => 'Curtume'], 
    //             ['br' => 'Bairro Socola'], 
    //             ['br' => 'Calawenda'],
    //             ['br' => 'Cazenga'],
    //             ['br' => 'Filda'],
    //             ['br' => 'Hoji Ya Henda'], 
    //             ['br' => 'Kalawenda'],
    //             ['br' => 'Kima Kieza'],
    //             ['br' => 'Levilagi'],
    //             ['br' => 'Mabor'],
    //             ['br' => 'Nocal'],
    //             ['br' => 'Ser Madó'], 
    //             ['br' => 'Sonefe'],
    //             ['br' => 'Tala Hadi']
    //         ],
    //         'IcoloeBengo' => [
    //             ['br' => 'Bela Vista'],
    //             ['br' => 'Bom Jesus'], 
    //             ['br' => 'Cabiri'], 
    //             ['br' => 'Caculo'],
    //             ['br' => 'Caculo Cahango'],
    //             ['br' => 'Cahango'],
    //             ['br' => 'Cassoneca'],
    //             ['br' => 'Catete'],
    //             ['br' => 'Quiminha']
    //         ],
    //         'Quissama' => [
    //             ['br' => 'Cabo Ledo'],
    //             ['br' => 'Chio'], 
    //             ['br' => 'Demba'], 
    //             ['br' => 'Demba Chio'],
    //             ['br' => 'Mumbondo'],
    //             ['br' => 'Muxima'],
    //             ['br' => 'Quixinge']
    //         ],
    //         'Talatona' => [
    //             ['br' => '11 de Novembro'], 
    //             ['br' => 'Benfica'],
    //             ['br' => 'Calemba'], 
    //             ['br' => 'Calemba 2'], 
    //             ['br' => 'Camama'],
    //             ['br' => 'Cidade Universitária'],
    //             ['br' => 'Fubú'],
    //             ['br' => 'Futungo de Belas'],
    //             ['br' => 'Ilha do Mussulo'],
    //             ['br' => 'Lar do Patriota'],
    //             ['br' => 'Talatona']
    //         ],
    //         'KilambaKiaxi' => [
    //             ['br' => 'Bairro Popular'], 
    //             ['br' => 'Capolo 2'],
    //             ['br' => 'Cassequel'],
    //             ['br' => 'Cassequel do Buraco'],
    //             ['br' => 'Cassequel do João Lourenço'],
    //             ['br' => 'Golf'], 
    //             ['br' => 'Golf 2'], 
    //             ['br' => 'Nova Vida'],
    //             ['br' => 'Palanca'],
    //             ['br' => 'Sapú'],
    //             ['br' => 'Teixera de Luanda'],
    //             ['br' => 'Vila Estoril']
    //         ],
    //         'Viana' => [
    //             ['br' => 'Baia'], 
    //             ['br' => 'Bairro da BCA Luanda'], 
    //             ['br' => 'Bairro do Huambo'], 
    //             ['br' => 'Bairro dos Bois'], 
    //             ['br' => 'Bairro Robaldina Luanda'], 
    //             ['br' => 'Calumbo'], 
    //             ['br' => 'Centralidade do Zango'], 
    //             ['br' => 'Estalagem'],
    //             ['br' => 'Gamek de Luanda'],
    //             ['br' => 'Grafanil de Luanda'],
    //             ['br' => 'Kikuxi'], 
    //             ['br' => 'Kilómetro 30'], 
    //             ['br' => 'Luanda Sul'], 
    //             ['br' => 'Sapu'], 
    //             ['br' => 'Vila da Mata'],
    //             ['br' => 'Vila de Viana'],
    //             ['br' => 'Vila Flor'],
    //             ['br' => 'Zango'],
    //             ['br' => 'Zango 1'],
    //             ['br' => 'Zango 2'],
    //             ['br' => 'Zango 3'],
    //             ['br' => 'Zango 4'],
    //             ['br' => 'Zango 5']
    //         ],
    //         'Luanda' => [
    //             ['br' => '1 de Maio'],
    //             ['br' => 'Alvalade'],
    //             ['br' => 'Cruzeiro'],
    //             ['br' => 'Bairro Azul'],
    //             ['br' => 'Bairro da Dona Malha'],
    //             ['br' => "Bairro das B'S e das C'S - Nelito Soares"],
    //             ['br' => "Bairro dos CTT'S Luanda"],
    //             ['br' => 'Bairro dos Kwanzas'],
    //             ['br' => 'Bairro Operário Luanda'],
    //             ['br' => 'Bairro Popular de Luanda'],
    //             ['br' => 'Bairro Uíge'],
    //             ['br' => 'Baleizão'],
    //             ['br' => 'Baía de Luanda'],
    //             ['br' => 'Baixa de Luanda'],
    //             ['br' => 'Boa Vista'],
    //             ['br' => 'Cassenda'],
    //             ['br' => 'Catambor'],
    //             ['br' => 'Campismo Luanda'],
    //             ['br' => 'Chaba'],
    //             ['br' => 'Cidade de Luanda'],
    //             ['br' => 'Codeme'],
    //             ['br' => 'Comandante Valódia - Combatentes'],
    //             ['br' => 'Complexo'],
    //             ['br' => 'Coqueiros'],
    //             ['br' => 'Coreia'],
    //             ['br' => 'Chicala 1'], 
    //             ['br' => 'Chicala 2'], 
    //             ['br' => 'Corimba'], 
    //             ['br' => 'EMCIB'],
    //             ['br' => 'Gamek'], 
    //             ['br' => 'Gamek a Direita'], 
    //             ['br' => 'Ilha de Luanda'], 
    //             ['br' => 'Ingombota'], 
    //             ['br' => 'Katinton'], 
    //             ['br' => 'Kinaxixi'], 
    //             ['br' => 'Maculusso'], 
    //             ['br' => 'Maianga'], 
    //             ['br' => 'Márteres do Kifangondo'],
    //             ['br' => 'Miramar'],
    //             ['br' => 'Marçal'],
    //             ['br' => 'Marçal de Luanda'],
    //             ['br' => 'Marginal de Luanda'],
    //             ['br' => 'Morro Bento'], 
    //             ['br' => 'Morro Bento 2'], 
    //             ['br' => 'Morro da Luz'], 
    //             ['br' => 'Mutamba'], 
    //             ['br' => 'Neves Bendinha'],
    //             ['br' => 'Ngola Kiluanje'], 
    //             ['br' => 'Nguanhã'],
    //             ['br' => 'Petrangol'],
    //             ['br' => 'Petrofe'],
    //             ['br' => 'Porto Pesqueiro da Boa Vista'],
    //             ['br' => 'Precol'],
    //             ['br' => 'Prenda'],
    //             ['br' => 'Rangel'], 
    //             ['br' => 'Rocha Padaria'], 
    //             ['br' => 'Rocha Pinto'], 
    //             ['br' => 'Sagrada Esperança'],
    //             ['br' => 'Samba'],
    //             ['br' => 'Sambizanga'],
    //             ['br' => 'São Paulo'],
    //             ['br' => 'São Pedro da Barra'],
    //             ['br' => 'Sambizanga'],
    //             ['br' => 'Tunga Ngó'],
    //             ['br' => 'Vila Alice'], 
    //             ['br' => 'Vila do Gamek'],
    //             ['br' => 'Zamba 2']
    //         ]
    // ];

    // 	return Arr::has($bairros, $municipio) ? $bairros[$municipio] : [['br' => '']];
    // }

}
