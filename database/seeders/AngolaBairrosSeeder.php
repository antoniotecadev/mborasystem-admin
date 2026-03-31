<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AngolaBairrosSeeder extends Seeder
{
    public function run()
    {
        $bairrosPorMunicipio = [
            'Belas' => [
                'Barra do Kwanza', 'Cabolombo', 'Ilha da Cazanga', 'Ilha dos Pássaros', 'Kilamba',
                'KK5000', 'Morro dos Veados', 'Quenguela', 'Ramiros', 'Vila Verde 1', 'Vila Verde 2',
            ],
            'Cacuaco' => [
                'Bairro de Chapa - Via Expressa', 'Bairro Vidrul Luanda', 'Balumuka', 'Boa Esperança',
                'Cacuaco', 'Cimangola', 'Funda', 'Kifangondo', 'Kikolo', 'Mulenvos Baixos', 'Muluéka',
                'Panguila', 'Paraíso', 'Sequele', 'Centralidade do Cacuaco',
            ],
            'Cazenga' => [
                '11 de Novembro', 'Asa Branca', 'Cala Boca', 'Cuca', 'Curtume', 'Bairro Socola', 'Calawenda',
                'Cazenga', 'Filda', 'Hoji Ya Henda', 'Kalawenda', 'Kima Kieza', 'Levilagi', 'Mabor',
                'Nocal', 'Ser Madó', 'Sonefe', 'Tala Hadi',
            ],
            'Icolo e Bengo' => [
                'Bela Vista', 'Bom Jesus', 'Cabiri', 'Caculo', 'Caculo Cahango', 'Cahango',
                'Cassoneca', 'Catete', 'Quiminha',
            ],
            'Quissama' => [
                'Cabo Ledo', 'Chio', 'Demba', 'Demba Chio', 'Mumbondo', 'Muxima', 'Quixinge',
            ],
            'Talatona' => [
                '11 de Novembro', 'Benfica', 'Calemba', 'Calemba 2', 'Camama', 'Cidade Universitária',
                'Fubú', 'Futungo de Belas', 'Ilha do Mussulo', 'Lar do Patriota', 'Talatona',
            ],
            'Quilamba Quiaxi' => [
                'Bairro Popular', 'Capolo 2', 'Cassequel', 'Cassequel do Buraco', 'Cassequel do João Lourenço',
                'Golf', 'Golf 2', 'Nova Vida', 'Palanca', 'Sapú', 'Teixeira de Luanda', 'Vila Estoril',
            ],
            'Viana' => [
                'Baia', 'Bairro da BCA Luanda', 'Bairro do Huambo', 'Bairro dos Bois', 'Bairro Robaldina Luanda',
                'Calumbo', 'Centralidade do Zango', 'Estalagem', 'Gamek de Luanda', 'Grafanil de Luanda',
                'Kikuxi', 'Kilómetro 30', 'Luanda Sul', 'Vila da Mata', 'Vila de Viana', 'Vila Flor',
                'Zango', 'Zango 1', 'Zango 2', 'Zango 3', 'Zango 4', 'Zango 5',
            ],
            'Luanda' => [
                '1 de Maio', 'Alvalade', 'Cruzeiro', 'Bairro Azul', 'Baixa de Luanda', 'Boa Vista',
                'Cassenda', 'Catambor', 'Cidade de Luanda', 'Ingombota', 'Kinaxixi', 'Maculusso',
                'Maianga', 'Miramar', 'Mutamba', 'Rangel', 'Rocha Pinto', 'Samba', 'Sambizanga',
                'São Paulo', 'Vila Alice',
            ],
        ];

        $municipios = DB::table('municipios')->select('id', 'nome')->get();

        foreach ($municipios as $municipio) {
            $bairros = $bairrosPorMunicipio[$municipio->nome] ?? ['Sede'];

            foreach ($bairros as $bairro) {
                if (!DB::table('bairros')
                    ->where('municipio_id', $municipio->id)
                    ->where('nome', $bairro)
                    ->exists()) {
                    DB::table('bairros')->insert([
                        'municipio_id' => $municipio->id,
                        'nome' => $bairro,
                    ]);
                }
            }
        }
    }
}
