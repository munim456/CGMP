# CGMP Admin Guide

Log in at `/admin` with the credentials provided by your web developer. Change your password under **Profile** the first time you log in.

## Everyday tasks

### Edit homepage text (hero, highlights, about, booking strip)
**Homepage sections** in the sidebar. Each section has its own editor:
- **Hero** — badge text, heading, subheading, two buttons, background image.
- **Highlights** — the four cards (Medical Treatment, Emergency Help, Medical Professionals, Qualified Doctors). Add/remove/reorder rows.
- **About** — intro paragraph, bullet points, stats (number + label, e.g. `5` / `Days a week`), image.
- **Booking strip** — heading, line of text, button label.

Changes are live immediately after saving.

### Post a news item
1. **Blog → New post**.
2. Fill title (URL is generated automatically), category, tags, excerpt and body.
3. Set status to *Published* and a publish date (future dates schedule the post).
4. Optionally add a featured image and SEO fields.

The three most recent posts appear on the homepage automatically.

### Add or edit a service page
**Services → New service**. Title, icon, short description (card) and full description (page). Deactivate to hide without deleting. Services appear on `/services`, the homepage grid and the sitemap.

### Update doctor details
**Doctors**. Name, role, qualifications, bio and photo. Inactive doctors disappear from the site but keep their record.

### Change phone, address, hours, HealthEngine link
**Settings**:
- *Clinic identity* — name, tagline, logo, favicon.
- *Contact details* — phone, fax, email, address lines, opening hours, map embed.
- *Booking* — HealthEngine profile URL and/or embed code. The embed is used on `/book-appointment` when present; otherwise a big outbound button is shown.
- *Social & footer*, *SEO defaults* (site meta description format, social share image).

### Site-wide notices
**Announcements** create coloured strips under the header (info/warning/urgent styles). Visitors can dismiss them for 7 days. Deactivate to remove instantly.

## Housekeeping

- **Media library** — upload images once, reuse everywhere. Uploads are auto-resized to max 1600px.
- **Messages** — every contact form submission is stored here even if email delivery fails. Delete after actioning (privacy).
- **Testimonials** — only published testimonials appear; the section hides itself when empty. Get written patient consent before publishing.
- **Static pages** — Privacy Policy and Terms of Use templates are seeded; edit their content in **Pages**. Create extra pages freely — they get URLs automatically.

## Rules of thumb

- Never put patient information anywhere on the website.
- Booking is always via HealthEngine — don't collect appointment requests through the contact form.
- The emergency notice ("call 000") on the contact page must stay.
- Homepage order (Hero → Blog → Highlights → About → Doctors → Notices → Testimonials → Booking strip → Footer) was approved by the practice — ask before changing it.
