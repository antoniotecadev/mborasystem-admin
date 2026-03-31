#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"

run_doctor=true
run_services=true
run_bootstrap=true

for arg in "$@"; do
  case "$arg" in
    --no-doctor) run_doctor=false ;;
    --no-services) run_services=false ;;
    --no-bootstrap) run_bootstrap=false ;;
    -h|--help)
      cat <<'EOF'
Uso:
  ./bin/up.sh [opções]

Opções:
  --no-doctor      Não executa diagnóstico (doctor)
  --no-services    Não sobe serviços Docker
  --no-bootstrap   Não executa bootstrap do Laravel/Node
  -h, --help       Mostra esta ajuda
EOF
      exit 0
      ;;
    *)
      echo "Opção inválida: $arg"
      echo "Use --help para ver opções disponíveis."
      exit 1
      ;;
  esac
done

cd "$ROOT_DIR"

echo "[1/3] Doctor"
$run_doctor && ./bin/doctor.sh || echo "(ignorado)"

echo "[2/3] Services"
$run_services && ./bin/services-up.sh || echo "(ignorado)"

echo "[3/3] Bootstrap"
$run_bootstrap && ./bin/bootstrap.sh || echo "(ignorado)"

echo "Pronto."
echo "App: http://127.0.0.1:8000"
echo "phpMyAdmin: http://127.0.0.1:8080"
