---
name: run-cgmp
description: Build, run, and drive the CGMP Laravel site (Cringila General Medical Practice). Use when asked to start the app, run migrations/seeders, take a screenshot of a page, hit a route, or verify the site renders after a change.
---

Laravel 12 + SQLite + Vite/Tailwind server-rendered site (public marketing
pages + an auth-gated `/admin` CMS). It has no SPA/JS framework.

Three layers of driver, pick based on what you're checking:

1. **`tests/Feature/SmokeTest.php`** (already in the repo, `composer test`
   runs it) — in-process, no server to boot. Renders every public URL
   *and every seeded record's page* (each doctor/service/post/CMS page by
   id or slug), renders every admin CRUD page as both `admin` and
   `manager` roles, and asserts the `manager`-forbidden pages (`/admin/settings`,
   `/admin/users`) actually 403. This is the first thing to run after any
   view/controller/route change — it catches more than a manual click-through would.
2. **`.claude/skills/run-cgmp/smoke.sh`** — boots the real
   `php artisan serve` and `curl`s it from outside the process. Use this
   when you need to confirm the actual HTTP server + built Vite assets
   work, not just the test harness (e.g. after `npm run build`, or to
   rule out a `.env`/routing-middleware issue that only shows up over
   real HTTP).
3. **`claude-in-chrome` browser MCP tool** — for an actual visual
   screenshot (no `chromium-cli`/Playwright installed in this
   environment). See "Visual check" below.

All paths below are relative to the repo root (`cgmp/cgmp/`).

## Prerequisites

Windows dev box with these already on PATH (verified versions — don't
assume a Linux container here, this project runs under Git Bash/PowerShell):

```bash
php -v        # PHP 8.2.12
composer -V   # Composer 2.10.2
node -v       # v24.18.0
npm -v        # 11.16.0
```

No OS packages needed beyond that — no sqlite3 CLI, no xvfb (nothing GUI).

## Setup

One-time, from a clean checkout (no `vendor/`, `node_modules/`, `.env`, or
`database/database.sqlite`):

```bash
composer install --no-interaction
npm install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate --ansi
php artisan migrate --force --ansi
php artisan db:seed --force --ansi   # demo doctors/services/pages + admin user
```

`db:seed` prints a freshly generated admin login, e.g.:
`admin@cgmp.local / <random-password>` — capture it from the output, it's
not stored anywhere else in plaintext.

## Build

```bash
npm run build   # vite build -> public/build/
```

Re-run after any change under `resources/`. There's no need to run
`npm run dev` (Vite HMR) for a one-off verification — a production build
is enough for `php artisan serve` to serve working CSS/JS.

## Run (agent path)

Fastest sanity check — no server, no browser, asserts status codes for
every page in the DB (46 tests, ~17s):

```bash
composer test
```

To actually boot the server and hit it over HTTP:

```bash
bash .claude/skills/run-cgmp/smoke.sh
```

What it does: starts `php artisan serve` on port 8000 (override with
`PORT=...`), polls `/` until it's up, curls 14 public routes plus
`/admin` (expects a 302 to `/login` since it's auth-gated), prints
`PASS`/`FAIL` per route, kills the server on exit, and exits non-zero if
anything failed. Requires `.env` to already exist (run Setup first).

Log of the last server run: `/tmp/cgmp_serve.log`.

If port 8000 is already bound by a leftover server from a prior session,
find and kill it first:

```bash
netstat -ano | grep ":8000" | grep LISTENING
taskkill //PID <pid> //F
```

## Visual check

No `chromium-cli`/Playwright in this environment. Use the `claude-in-chrome`
MCP tool instead — start the server manually (smoke.sh kills it on exit,
so don't use that for this), then:

1. `mcp__claude-in-chrome__tabs_context_mcp` (`createIfEmpty: true`)
2. `mcp__claude-in-chrome__navigate` to `http://127.0.0.1:8000/`
3. `mcp__claude-in-chrome__computer` with `action: screenshot`

This is what was used to verify the homepage, `/services`, and `/doctors`
render correctly (nav, hero, card galleries) after the Oct 2026 redesign.
First screenshot after a cold navigate can time out once (~30s) while
Chrome finishes loading — just retake it, don't treat it as a failure.

## Run (human path)

```bash
composer run dev
```

Runs `php artisan serve` + queue listener + `pail` log tailer + `vite`
dev server concurrently (via `npx concurrently`). Ctrl-C to stop. Useless
headless — it's for a human with a browser.

## Gotchas

- **Fresh clone has nothing runnable.** No `vendor/`, `node_modules/`,
  `.env`, or sqlite file ship in git — all of Setup is mandatory, not
  optional, before `php artisan serve` will even boot.
- **`db:seed` admin password is random per run.** `AdminUserSeeder`
  generates it fresh each time and only prints it to stdout — re-seeding
  invalidates the previous one.
- **`disown`-ing the background server detaches it from this harness's
  task tracker**, which reports the launch task as "completed" immediately
  even though the `php artisan serve` process keeps running in the
  background. Don't take that notification as "server stopped" — check
  with `curl` or `netstat` instead.
- **smoke.sh's `trap cleanup EXIT` kills the server when the script
  exits** — don't run smoke.sh if you intend to keep the server up
  afterward for a manual/browser check; start `php artisan serve`
  directly instead.

## Troubleshooting

- **`curl: (7) Failed to connect` right after launching `php artisan
  serve`**: it takes ~1-2s to bind; poll with the same `until curl -sf`
  loop smoke.sh uses instead of a fixed sleep.
- **Port 8000 already in use** (leftover server from a previous session
  that was `disown`ed): `netstat -ano | grep ":8000"` then
  `taskkill //PID <pid> //F`.
