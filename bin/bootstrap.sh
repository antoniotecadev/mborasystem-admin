#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

COMPOSER_BIN="composer"
if ! command -v composer >/dev/null 2>&1 && [ -x "$HOME/.local/bin/composer" ]; then
  COMPOSER_BIN="$HOME/.local/bin/composer"
fi

# .env
[ -f .env ] || cp .env.example .env

# Composer deps
"$COMPOSER_BIN" install --no-interaction

# Node deps + build
if [ -f .nvmrc ] && [ -s "$HOME/.nvm/nvm.sh" ]; then
  # shellcheck disable=SC1090
  . "$HOME/.nvm/nvm.sh"
  nvm install "$(cat .nvmrc)" >/dev/null
  nvm use "$(cat .nvmrc)" >/dev/null
fi
npm install --legacy-peer-deps
npm run dev

# Laravel init
php artisan key:generate
php artisan config:clear
php artisan migrate --force
php artisan db:seed

echo "bootstrap concluído"
