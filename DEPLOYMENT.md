# Hostinger Deployment Guide

Deploy the CGMP website to Hostinger shared/premium hosting. Two paths: **A) with SSH** (recommended, fastest) or **B) without SSH** (file upload only).

---

## Before you start

On your local machine, from the project root:

```powershell
npm install
npm run build
.\deploy\build-package.ps1     # creates dist\cgmp-deploy.zip
```

In hPanel you will need:
1. **PHP version 8.2+** for the domain (Websites → PHP Configuration).
2. A **MySQL database**: Databases → Management → create DB + user, note the name/user/password (Hostinger prefixes them, e.g. `u123456789_cgmp`).
3. The **document root** pointed at the site's `/public` folder (see step 3 below).

---

## Path A — with SSH

hPanel → Hosting plan details → **SSH access**; get host/port/key from "SSH details". Then:

```bash
# 1. Upload the package
scp dist/cgmp-deploy.zip u123456789@ssh.hostinger.com:~/cgmp-deploy.zip
ssh u123456789@ssh.hostinger.com

# 2. Extract OUTSIDE public_html
cd ~ && mkdir -p apps && cd apps
unzip ~/cgmp-deploy.zip -d cgmp && cd cgmp

# 3. Point document root at the public folder
#    hPanel -> Websites -> Manage -> no direct folder choice on some plans;
#    easiest: replace default index.php in public_html with a front controller.
```

**Document root options** (pick one):

- *If hPanel lets you edit the docroot* (some plans): set it to `~/apps/cgmp/public`.
- *Otherwise (most shared plans)*: make `public_html` a symlink target by placing a front controller inside it.

Front-controller fallback — create `public_html/.htaccess` and `public_html/index.php`:

```apache
# public_html/.htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ apps/cgmp/public/$1 [L]
</IfModule>
```

```php
<?php // public_html/index.php
require __DIR__.'/apps/cgmp/vendor/autoload.php';
$app = require_once __DIR__.'/apps/cgmp/bootstrap/app.php';
$app->handleRequest();
```

```bash
# 4. Environment + first boot
cd ~/apps/cgmp
cp .env.example .env            # then edit DB creds, APP_URL, SMTP password
nano .env
php artisan key:generate --force
php artisan migrate --force          # NOT --seed on redeploys
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

> Seed **once** (`php artisan db:seed --force`) if starting with an empty CMS. Never re-run seeders after content has been edited.

### Updates (Path A)

```powershell
npm run build ; .\deploy\build-package.ps1
scp dist/cgmp-deploy.zip ...
```
then on the server: extract over the old folder, `php artisan migrate --force`, `php artisan config:cache route:cache view:cache`. Uploaded images live in `storage/` — the zip never touches it.

---

## Path B — without SSH (file manager)

1. Extract `dist\cgmp-deploy.zip` on your PC.
2. In hPanel File Manager, upload the extracted folders (`app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`, `storage`, `vendor`, plus files) to a folder like `~/apps/cgmp`.
3. Copy `public/index.php` + `public/.htaccess` into `public_html`, then edit the copied `index.php`: change the two `__DIR__.'/'` paths to point at `../apps/cgmp/...`:

```php
require __DIR__.'/../apps/cgmp/vendor/autoload.php';
$app = require_once __DIR__.'/../apps/cgmp/bootstrap/app.php';
```

4. Copy `.env.example` to `.env` in `~/apps/cgmp`, fill in DB credentials, `APP_URL=https://cgmp.com.au`, SMTP password.
5. Generate APP_KEY locally: run `php artisan key:generate --show` on your machine and paste the value into the server's `.env`.
6. Import the schema: locally run `php artisan migrate:fresh --seed` against a dump… simplest is to export your local MySQL DB (e.g. via phpMyAdmin) as SQL and import it through hPanel phpMyAdmin into the production database.
7. Create the storage symlink manually: in `public_html`… actually the link must be inside Laravel's `public`. If the front controller lives in `public_html`, create folder `public_html/storage` as a **symlink** via File Manager is not possible — instead add this route-free fallback to `public_html/.htaccess`:

```apache
RewriteRule ^storage/(.*)$ apps/cgmp/storage/app/public/$1 [L]
```

8. Set permissions: File Manager → right-click `storage` and `bootstrap/cache` → Permissions → 775 (apply recursively).

---

## Post-deploy checklist

- [ ] Site loads at https://cgmp.com.au (padlock valid — SSL via hPanel → Security → SSL)
- [ ] `/admin` login works; **change the seeded password immediately**
- [ ] Upload logo/favicon/OG image in Settings; verify phone/address/HealthEngine URL placeholders replaced
- [ ] Submit a test contact message → arrives at reception inbox AND appears under Admin → Messages
- [ ] Book button opens HealthEngine
- [ ] `APP_DEBUG=false` in `.env`; visit a bad URL → styled 404, not stack trace
- [ ] Run Lighthouse on mobile (target 85+ accessibility/perf)
- [ ] Cron entry added (below) so scheduled blog posts publish and nightly backups actually run

## Scheduled tasks (cron)

The app relies on Laravel's scheduler for two things: publishing blog posts at their
scheduled time (`posts:publish-scheduled`) and a nightly database backup (`backup:run`,
see below). Neither runs on its own — hPanel → **Advanced → Cron Jobs** needs one entry
that fires every minute and lets Laravel decide what's actually due:

```bash
* * * * * cd ~/apps/cgmp && php artisan schedule:run >> /dev/null 2>&1
```

(Adjust the path if you deployed elsewhere. If hPanel's PHP CLI binary differs from the
web one, use the full path shown in hPanel → PHP Configuration, e.g. `/usr/bin/php8.2`.)

### Database backups

`php artisan backup:run` writes a timestamped, gzip-compressed SQL dump to
`storage/app/backups/` (outside the web root — not publicly reachable) and keeps the 14
most recent by default (`--keep=N` to change). It runs nightly at 03:00 via the cron
entry above. Since Hostinger shared hosting doesn't reliably offer `ext-zip`/`ext-pcntl`
or shell access to `mysqldump`, the command dumps through Laravel's own DB connection —
no extra PHP extensions or binaries required.

Periodically download `storage/app/backups/*.sql.gz` off-server (e.g. via SFTP) — local
disk backups protect against bad migrations or accidental deletes, not against losing the
whole server. To restore one: `gunzip -c backup-*.sql.gz | mysql -u USER -p DBNAME`.

## Troubleshooting

| Symptom | Fix |
|---|---|
| 500 blank page | Check `storage/logs/laravel.log`; usually wrong DB creds or missing APP_KEY |
| CSS/JS 404s | `public/build` wasn't uploaded, or mixed http/https → set `ASSET_URL=https://cgmp.com.au` |
| Images uploaded in admin don't display | Storage link/rule missing (step A3/B7) |
| Email not sending | Hostinger requires the From address to match a real mailbox; use `smtp.hostinger.com:465` SSL |
