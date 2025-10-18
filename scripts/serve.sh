#!/usr/bin/env bash
set -euo pipefail
PORT="${1:-8000}"
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
if ! command -v php >/dev/null 2>&1; then
  echo "PHP is not installed in this environment." >&2
  echo "You can still deploy on a PHP server or run locally with PHP installed." >&2
  exit 1
fi
php -S 0.0.0.0:"$PORT" -t "$DIR/public" "$DIR/public/index.php"
