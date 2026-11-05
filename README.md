# Personal Portfolio Website

A complete, modern, responsive personal portfolio website with a separate admin panel, built with **PHP 8+**, **MySQL**, **HTML5**, **CSS3**, and **JavaScript**.

Compatible with **InfinityFree** free web hosting (no Node.js, npm, or VPS required).

## Features

### Public Website
- **About** – Large profile card with photo, contact links (email, phone, GitHub)
- **Education** – Three-column education cards (desktop) / responsive grid
- **Skills** – Modern rounded skill badges with purple indicators
- **Certifications & Seminars** – Certificate cards with images/PDFs
- Dark navy theme with purple/blue gradient accents and cyan highlights
- Fully responsive navigation (desktop, tablet, mobile)
- Smooth scrolling and hover animations
- Minimal centered footer

### Admin Panel (Password Protected)
- Admin login with PHP sessions & `password_hash()` / `password_verify()`
- **Overview Dashboard** – record counts, quick navigation, recent activity
- **About Management** – edit name, position, email, phone, GitHub, profile image
- **Education Management** – add, edit, delete education records
- **Skills Management** – add, edit, delete skills
- **Certifications Management** – add, edit, delete certifications with image/PDF upload
- **Website Settings** – logo text, footer text, site name
- CSRF protection, prepared statements, input validation, secure file uploads
- Activity logging

### Database
Tables: `admins`, `profile`, `education`, `skills`, `certifications`, `website_settings`, `activity_logs`

## Folder Structure

```
portfolio/
├── index.php
├── config.php
├── database.sql
├── README.md
├── config/
│   └── database.php
├── assets/
│   ├── css/style.css
│   ├── js/script.js
│   ├── images/
│   └── uploads/
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── functions.php
│   └── auth.php
└── admin/
    ├── login.php
    ├── logout.php
    ├── index.php
    ├── about.php
    ├── education.php
    ├── skills.php
    ├── certifications.php
    └── settings.php
```

## Installation (InfinityFree)

1. **Upload files** – Upload all files to your InfinityFree `htdocs` directory (except `database.sql`).
2. **Protect config** – The `config/database.php` file is outside the web root pattern below is fine on InfinityFree; ensure `.htaccess` denies access to `config/` and `includes/`.
3. **Create database** – In InfinityFree's phpMyAdmin, create a new MySQL database and import `database.sql`.
4. **Configure credentials** – Open `config/database.php` and set `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` to match your InfinityFree MySQL details.
5. **Create uploads folder** – Ensure `assets/uploads/` exists and is writable (CHMOD 755).
6. **Visit site** – Open your domain to view the portfolio.
7. **Admin login** – Visit `yourdomain.com/admin/login.php`.

### Default Admin Credentials (from database.sql)

- Username: `admin`
- Password: `admin123`

**Change this password immediately after first login.**

## Security

- Passwords hashed with `password_hash()`
- Prepared statements via PDO
- CSRF tokens on all state-changing forms
- File type validation on uploads
- Admin routes protected by session authentication
- `config/` and `includes/` should be blocked from direct web access via `.htaccess`

## .htaccess (Recommended)

Add this to your `htdocs/.htaccess` to protect sensitive directories:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    # Block direct access to config and includes
    RewriteRule ^(config|includes)/ - [F,L]
</IfModule>
Options -Indexes
```

## License

MIT
