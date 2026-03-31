# Scripts de Setup Local

## `doctor.sh`
Faz diagnóstico do ambiente local:

- verifica `php`, `composer`, `node`, `npm`, `docker`
- confirma se o `docker daemon` está acessível
- confirma presença de `.env`, `vendor`, `node_modules`
- mostra `[OK]` / `[WARN]` para indicar o que falta

## `services-up.sh`
Sobe serviços Docker necessários:

- `mbora-mariadb` na porta `3307`
- `mbora-phpmyadmin` na porta `8080`
- se já existir container parado, faz `start`; se não existir, cria

## `bootstrap.sh`
Prepara o projeto Laravel/Node do zero:

- cria `.env` se não existir
- instala dependências PHP (`composer install`)
- usa versão Node da `.nvmrc` (se tiver `nvm`)
- instala dependências JS (`npm install --legacy-peer-deps`)
- compila assets (`npm run dev`)
- gera `APP_KEY`, limpa cache de configuração
- executa `migrate` e `db:seed`

## `up.sh`
Orquestrador único:

- chama `doctor` → `services-up` → `bootstrap`
- suporta flags:
	- `--no-doctor`
	- `--no-services`
	- `--no-bootstrap`
- é o comando mais simples para novo PC

## `Makefile`
Já existia; foi adicionado o alvo:

- `make up` → executa `up.sh`

## `.nvmrc`
Define a versão Node recomendada (`16`) para reduzir conflitos de build no outro PC.