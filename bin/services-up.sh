#!/usr/bin/env bash
set -euo pipefail

if ! command -v docker >/dev/null; then
  echo "docker não encontrado"
  exit 1
fi

# MariaDB
if ! docker ps --format '{{.Names}}' | grep -q '^mbora-mariadb$'; then
  if docker ps -a --format '{{.Names}}' | grep -q '^mbora-mariadb$'; then
    docker start mbora-mariadb >/dev/null
  else
    docker run -d --name mbora-mariadb \
      -e MARIADB_ROOT_PASSWORD=root \
      -e MARIADB_DATABASE=mborasystem_admin \
      -p 3307:3306 \
      mariadb:10.6 >/dev/null
  fi
fi

# phpMyAdmin
if ! docker ps --format '{{.Names}}' | grep -q '^mbora-phpmyadmin$'; then
  if docker ps -a --format '{{.Names}}' | grep -q '^mbora-phpmyadmin$'; then
    docker start mbora-phpmyadmin >/dev/null
  else
    docker run -d --name mbora-phpmyadmin \
      --link mbora-mariadb:db \
      -e PMA_HOST=db \
      -e PMA_USER=root \
      -e PMA_PASSWORD=root \
      -p 8080:80 \
      phpmyadmin >/dev/null
  fi
fi

echo "serviços ativos:"
docker ps --format 'table {{.Names}}\t{{.Status}}\t{{.Ports}}' | grep -E 'NAMES|mbora-mariadb|mbora-phpmyadmin'
