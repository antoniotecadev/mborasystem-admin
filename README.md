# MboraSystem Admin

Projeto Laravel/Inertia para gestão do MboraSystem.

## Setup rápido

```bash
make up
```

Esse comando executa:

1. `doctor` — verifica o ambiente
2. `services-up` — sobe MariaDB e phpMyAdmin via Docker
3. `bootstrap` — instala dependências, migra e faz seed

## Comandos úteis

```bash
make doctor
make services-up
make bootstrap
make serve
make test
```

## Portas locais

- App Laravel: `http://127.0.0.1:8000`
- phpMyAdmin: `http://127.0.0.1:8080`

## Requisitos

- PHP
- Composer
- Node.js `16` (via `.nvmrc`)
- npm
- Docker

## Notas

- O banco local é MariaDB em Docker.
- O login inicial é criado via `DatabaseSeeder`.
- Para importar dados geográficos de Angola, usa o comando `geo:import-angola`.

## Scripts auxiliares

A documentação detalhada dos scripts está em `bin/readme.md`.
