#!/usr/bin/env bash
# Boots the Laravel dev server and curls the public + admin-gated routes.
# Run from anywhere; resolves the repo root relative to this script.
set -uo pipefail

SKILL_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "$SKILL_DIR/../../.." && pwd)"
cd "$ROOT"

PORT="${PORT:-8000}"
BASE="http://127.0.0.1:$PORT"
LOG="/tmp/cgmp_serve.log"
PID_FILE="/tmp/cgmp_serve.pid"

if [ ! -f .env ]; then
  echo "No .env found — run the Setup steps in SKILL.md first." >&2
  exit 1
fi

php artisan serve --port="$PORT" > "$LOG" 2>&1 &
SERVER_PID=$!
echo "$SERVER_PID" > "$PID_FILE"

cleanup() {
  kill "$SERVER_PID" 2>/dev/null || true
}
trap cleanup EXIT

if ! timeout 30 bash -c "until curl -sf '$BASE/' >/dev/null 2>&1; do sleep 0.5; done"; then
  echo "Server never came up. Log:" >&2
  cat "$LOG" >&2
  exit 1
fi

routes=(/ /about /doctors /services /blog /contact /faq /book-appointment /privacy-policy /terms /fees-info /login /register /sitemap.xml)
fail=0
for r in "${routes[@]}"; do
  code=$(curl -s -o /dev/null -w "%{http_code}" "$BASE$r")
  if [ "$code" = "200" ]; then
    echo "PASS $r -> $code"
  else
    echo "FAIL $r -> $code"
    fail=1
  fi
done

# Admin area is auth-gated — unauthenticated hit must redirect to /login, not 200.
redirect=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/admin")
if [ "$redirect" = "302" ]; then
  echo "PASS /admin (unauthenticated) -> 302 redirect"
else
  echo "FAIL /admin (unauthenticated) -> $redirect (expected 302)"
  fail=1
fi

exit $fail
