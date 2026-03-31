<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AngolaMunicipiosSeeder extends Seeder
{
    public function run()
    {
        $mapa = [
            'Bengo' => ['Ambriz', 'Bula Atumba', 'Dande', 'Dembos-Quibaxe', 'Nambuangongo', 'Pango Aluquém'],
            'Benguela' => ['Balombo', 'Baía Farta', 'Benguela', 'Bocoio', 'Caimbambo', 'Catumbela', 'Chongorói', 'Cubal', 'Ganda', 'Lobito'],
            'Bié' => ['Andulo', 'Camacupa', 'Catabola', 'Chinguar', 'Chitembo', 'Cuemba', 'Cunhinga', 'Cuíto', "N'harea"],
            'Cabinda' => ['Belize', 'Buco-Zau', 'Cabinda', 'Cacongo'],
            'Cubango' => ['Cuchi', 'Cuito Cuanavale', 'Mavinga', 'Menongue'],
            'Cuando' => ['Calai', 'Cuangar', 'Dirico', 'Nancova', 'Rivungo'],
            'Cuanza Norte' => ['Ambaca', 'Banga', 'Bolongongo', 'Cambambe', 'Cazengo', 'Golungo Alto', 'Gonguembo', 'Lucala', 'Quiculungo', 'Samba Cajú'],
            'Cuanza Sul' => ['Amboim', 'Cassongue', 'Cela', 'Waku Kungo', 'Conda', 'Ebo', 'Libolo', 'Mussende', 'Quibala', 'Quilenda', 'Seles', 'Sumbe'],
            'Cunene' => ['Cahama', 'Cuanhama', 'Curoca', 'Cuvelai', 'Namacunde', 'Ombadja'],
            'Huambo' => ['Bailundo', 'Caála', 'Katchiungo', 'Ekunha', 'Huambo', 'Londuimbale', 'Longonjo', 'Mungo', 'Tchicala-Tcholoanga', 'Tchindjenje', 'Ucuma'],
            'Huíla' => ['Caconda', 'Caluquembe', 'Chiange', 'Chibia', 'Chicomba', 'Chipindo', 'Gambos', 'Humpata', 'Jamba', 'Kuvango', 'Lubango', 'Matala', 'Quilengues', 'Quipungo'],
            'Icolo e Bengo' => ['Icolo e Bengo'],
            'Luanda' => ['Belas', 'Cacuaco', 'Cazenga', 'Luanda', 'Quilamba Quiaxi', 'Quissama', 'Talatona', 'Viana'],
            'Lunda Norte' => ['Cambulo', 'Capenda-Camulemba', 'Caungula', 'Chitato', 'Cuango', 'Cuilo', 'Lóvua', 'Lubalo', 'Lucapa', 'Xá-Muteba'],
            'Lunda Sul' => ['Cacolo', 'Dala', 'Muconda', 'Saurimo'],
            'Malanje' => ['Cacuso', 'Calandula', 'Cambundi-Catembo', 'Cangandala', 'Caombo', 'Cunda-dia-baze', 'Kiwaba Nzoji', 'Luquembo', 'Malanje', 'Marimba', 'Massango', 'Mucari', 'Quela', 'Quirima'],
            'Moxico' => ['Camanongue', 'Cameia', 'Leua', 'Luena', 'Luacano'],
            'Moxico Leste' => ['Alto Zambeze', 'Luau', 'Lumbala Nguimbo', 'Luchazes'],
            'Namibe' => ['Bibala', 'Camacuio', 'Moçâmedes', 'Tômbua', 'Virei'],
            'Uíge' => ['Ambuila', 'Bembe', 'Buengas', 'Bungo', 'Cangola', 'Damba', 'Mucaba', 'Negage', 'Puri', 'Quimbele', 'Quitexe', 'Santa Cruz', 'Sanza Pombo', 'Songo', 'Uíge', 'Maquela do Zombo'],
            'Zaire' => ['Cuimba', "M'Banza Kongo", 'Noqui', "N'Zeto", 'Soyo', 'Tomboco'],
        ];

        foreach ($mapa as $provinciaNome => $municipios) {
            $provinciaId = DB::table('provincias')->where('nome', $provinciaNome)->value('id');

            if (!$provinciaId) {
                continue;
            }

            foreach ($municipios as $municipioNome) {
                if (!DB::table('municipios')
                    ->where('provincia_id', $provinciaId)
                    ->where('nome', $municipioNome)
                    ->exists()) {
                    DB::table('municipios')->insert([
                        'provincia_id' => $provinciaId,
                        'nome' => $municipioNome,
                    ]);
                }
            }
        }
    }
}
