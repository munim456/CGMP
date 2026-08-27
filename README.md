# CGMP — Cringila General Medical Practice Website

Laravel 12 rebuild of cgmp.com.au. All content is editable through the built-in admin panel at `/admin` — no text or images are hard-coded.

## Requirements

- PHP 8.2+ (XAMPP 8.2.12 used locally) with `pdo_mysql`, `gd`, `mbstring`, `openssl`, `fileinfo`
- Composer 2.x
- Node 18+ (24 used)
- MySQL 8.x

## Local setup

```bash
composer install
npm install
cp .env.example .env        # then edit DB credentials
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan serve           # http://127.0.0.1:8000
```

Local MySQL in this environment is the standalone **MySQL84** service on `127.0.0.1:3306`, root password `1234`, database `cgmp`. XAMPP's bundled `mysql.exe` client cannot connect to it (caching_sha2_password) — use Laravel/PHP PDO only.

### Admin login

- URL: `/admin`
- Seeded user: `admin@cgmp.com.au` / `ChangeMe123!`
- **Change this password immediately** (Admin → Profile).

Mail uses `MAIL_MAILER=log` locally; contact messages are always stored in the `contact_messages` table and visible under Admin → Messages.

## Content model

| Admin area | Backing table | Notes |
|---|---|---|
| Settings | `settings` | Clinic identity, contact details, HealthEngine link/embed, socials, SEO defaults, logo/favicon/OG image uploads |
| Homepage sections | `sections` | JSON blobs keyed `hero`, `highlights`, `about`, `booking_strip` |
| Services | `services` | Specialty pages (`/services/{slug}`), icon + short/long description |
| Doctors | `doctors` | Name, role, qualifications, bio, photo |
| Blog | `posts/categories/tags` | Draft/published, scheduled publish dates |
| Announcements | `announcements` | Site-wide strips under the header |
| Testimonials | `testimonials` | Only render when rows exist |
| Static pages | `pages` | About, Privacy Policy, Terms of Use + custom pages via catch-all route |

Homepage section order is client-approved and fixed: Hero → **Blog** → Highlights → About/stats → Doctors → Notices → Testimonials → Booking CTA → Footer. Do not reorder without written approval.

All booking goes through HealthEngine (`/book-appointment` renders the embed if set, else links out). No patient data is stored anywhere on this site.

## Colour palettes

Three palettes ship as CSS variable sets in `resources/css/public-base.css`. The active palette comes from the `palette` setting (`teal` default, `ocean`, `green`) applied as `<html data-palette="...">`. To add one, copy a `[data-palette]` block, define the same variables, and add the choice to the palette dropdown in `admin/settings`.

## Placeholders to verify with the practice before launch

- Phone number `(02) XXXX XXXX`
- Street address `[Street address — verify with practice]`
- HealthEngine profile URL / embed code
- Logo, favicon, doctor photos, clinic interior photos
- Testimonial wording/consent
- ABN/practice numbers if shown in footer

## Deployment (Hostinger)

1. Build assets locally: `npm run build`.
2. Upload the project (or deploy via git). Point the domain's document root at `/public`.
3. On shared hosting set `.env`: production DB credentials, `APP_ENV=production`, `APP_DEBUG=false`, real SMTP for contact mail, correct `APP_URL`.
4. `php artisan migrate --force --seed` (seed once), `php artisan storage:link`, `php artisan config:cache route:cache view:cache`.
5. Ensure `storage/` and `bootstrap/cache` are writable.
6. Upload logo/favicon/OG image via admin after first login.
7. Add a cron entry so scheduled blog posts publish automatically: `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`. Without this, posts saved with status "Scheduled" stay unpublished past their scheduled time.

See `docs/ADMIN-GUIDE.md` for the content-editor walkthrough.
