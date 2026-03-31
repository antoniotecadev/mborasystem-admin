#!/usr/bin/env bash
set -euo pipefail

ok() { printf "[OK] %s\n" "$1"; }
warn() { printf "[WARN] %s\n" "$1"; }

cd "$(dirname "$0")/.."

COMPOSER_BIN="composer"
if ! command -v composer >/dev/null 2>&1 && [ -x "$HOME/.local/bin/composer" ]; then
  COMPOSER_BIN="$HOME/.local/bin/composer"
fi

command -v php >/dev/null && ok "php: $(php -v | head -n1)" || warn "php não encontrado"
if command -v "$COMPOSER_BIN" >/dev/null 2>&1; then
  ok "composer: $($COMPOSER_BIN --version | head -n1)"
else
  warn "composer não encontrado"
fi
command -v node >/dev/null && ok "node: $(node -v)" || warn "node não encontrado"
command -v npm >/dev/null && ok "npm: $(npm -v)" || warn "npm não encontrado"

if command -v docker >/dev/null; then
  ok "docker: $(docker --version)"
  if docker ps >/dev/null 2>&1; then
    ok "docker daemon: ativo"
  else
    warn "docker instalado, mas daemon sem acesso"
  fi
else
  warn "docker não encontrado"
fi

if [ -f .env ]; then
  ok ".env presente"
else
  warn ".env ausente (será criado por bootstrap)"
fi

if [ -d vendor ]; then
  ok "vendor presente"
else
  warn "vendor ausente"
fi

if [ -d node_modules ]; then
  ok "node_modules presente"
else
  warn "node_modules ausente"
fi
