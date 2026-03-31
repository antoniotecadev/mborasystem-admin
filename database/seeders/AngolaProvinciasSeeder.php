<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AngolaProvinciasSeeder extends Seeder
{
    public function run()
    {
        $provincias = [
            'Bengo',
            'Benguela',
            'Bié',
            'Cabinda',
            'Cubango',
            'Cuando',
            'Cuanza Norte',
            'Cuanza Sul',
            'Cunene',
            'Huambo',
            'Huíla',
            'Icolo e Bengo',
            'Luanda',
            'Lunda Norte',
            'Lunda Sul',
            'Malanje',
            'Moxico',
            'Moxico Leste',
            'Namibe',
            'Uíge',
            'Zaire',
        ];

        foreach ($provincias as $nome) {
            if (!DB::table('provincias')->where('nome', $nome)->exists()) {
                DB::table('provincias')->insert(['nome' => $nome]);
            }
        }
    }
}
