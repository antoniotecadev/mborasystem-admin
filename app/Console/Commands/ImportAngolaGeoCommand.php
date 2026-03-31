<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportAngolaGeoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * --path: caminho para ficheiro .json ou .csv
     * --truncate: limpa tabelas antes de importar
     */
    protected $signature = 'geo:import-angola {--path= : Caminho do ficheiro JSON/CSV} {--truncate : Limpa provincias/municipios/bairros antes de importar}';

    /**
     * The console command description.
     */
    protected $description = 'Importa dados geográficos de Angola (províncias, municípios e bairros) a partir de JSON ou CSV.';

    public function handle(): int
    {
        $path = $this->option('path') ?: base_path('database/data/angola_geo.json');

        if (!file_exists($path)) {
            $this->error("Ficheiro não encontrado: {$path}");
            $this->line('Exemplo: php artisan geo:import-angola --path=database/data/angola_geo.sample.json --truncate');
            return self::FAILURE;
        }

        if ($this->option('truncate')) {
            DB::table('bairros')->truncate();
            DB::table('municipios')->truncate();
            DB::table('provincias')->truncate();
            $this->info('Tabelas limpas: provincias, municipios, bairros.');
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        try {
            if ($ext === 'json') {
                $this->importFromJson($path);
            } elseif ($ext === 'csv') {
                $this->importFromCsv($path);
            } else {
                $this->error('Formato não suportado. Use .json ou .csv');
                return self::FAILURE;
            }
        } catch (\Throwable $e) {
            $this->error('Falha na importação: '.$e->getMessage());
            return self::FAILURE;
        }

        $this->info('Importação concluída com sucesso.');
        $this->line('provincias='.DB::table('provincias')->count().' municipios='.DB::table('municipios')->count().' bairros='.DB::table('bairros')->count());

        return self::SUCCESS;
    }

    private function importFromJson(string $path): void
    {
        $payload = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $provincias = $payload['provincias'] ?? [];

        foreach ($provincias as $provincia) {
            $provinciaNome = trim((string)($provincia['nome'] ?? ''));
            if ($provinciaNome === '') {
                continue;
            }

            $provinciaId = $this->upsertProvincia($provinciaNome);

            foreach (($provincia['municipios'] ?? []) as $municipio) {
                $municipioNome = trim((string)($municipio['nome'] ?? ''));
                if ($municipioNome === '') {
                    continue;
                }

                $municipioId = $this->upsertMunicipio($provinciaId, $municipioNome);

                $bairros = $municipio['bairros'] ?? ['Sede'];
                if (!is_array($bairros) || empty($bairros)) {
                    $bairros = ['Sede'];
                }

                foreach ($bairros as $bairroNome) {
                    $bairroNome = trim((string)$bairroNome);
                    if ($bairroNome !== '') {
                        $this->upsertBairro($municipioId, $bairroNome);
                    }
                }
            }
        }
    }

    private function importFromCsv(string $path): void
    {
        $h = fopen($path, 'r');
        if (!$h) {
            throw new \RuntimeException('Não foi possível abrir o CSV.');
        }

        $header = fgetcsv($h);
        if (!$header) {
            fclose($h);
            throw new \RuntimeException('CSV vazio.');
        }

        $indexes = array_flip(array_map('strtolower', $header));
        foreach (['provincia', 'municipio', 'bairro'] as $required) {
            if (!array_key_exists($required, $indexes)) {
                fclose($h);
                throw new \RuntimeException("CSV deve conter colunas: provincia, municipio, bairro");
            }
        }

        while (($row = fgetcsv($h)) !== false) {
            $provinciaNome = trim((string)($row[$indexes['provincia']] ?? ''));
            $municipioNome = trim((string)($row[$indexes['municipio']] ?? ''));
            $bairroNome = trim((string)($row[$indexes['bairro']] ?? ''));

            if ($provinciaNome === '' || $municipioNome === '') {
                continue;
            }

            if ($bairroNome === '') {
                $bairroNome = 'Sede';
            }

            $provinciaId = $this->upsertProvincia($provinciaNome);
            $municipioId = $this->upsertMunicipio($provinciaId, $municipioNome);
            $this->upsertBairro($municipioId, $bairroNome);
        }

        fclose($h);
    }

    private function upsertProvincia(string $nome): int
    {
        $id = DB::table('provincias')->where('nome', $nome)->value('id');
        if ($id) {
            return (int)$id;
        }

        return (int) DB::table('provincias')->insertGetId(['nome' => $nome]);
    }

    private function upsertMunicipio(int $provinciaId, string $nome): int
    {
        $id = DB::table('municipios')
            ->where('provincia_id', $provinciaId)
            ->where('nome', $nome)
            ->value('id');

        if ($id) {
            return (int)$id;
        }

        return (int) DB::table('municipios')->insertGetId([
            'provincia_id' => $provinciaId,
            'nome' => $nome,
        ]);
    }

    private function upsertBairro(int $municipioId, string $nome): void
    {
        $exists = DB::table('bairros')
            ->where('municipio_id', $municipioId)
            ->where('nome', $nome)
            ->exists();

        if (!$exists) {
            DB::table('bairros')->insert([
                'municipio_id' => $municipioId,
                'nome' => $nome,
            ]);
        }
    }
}
