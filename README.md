# Carpet Company

Luxury, light-first Persian carpet ecommerce and corporate showcase built with Laravel 13 and PHP 8.4.

## Stack

- Laravel 13 / PHP 8.4
- Blade + lightweight vanilla JavaScript
- Spatie Laravel Permission for roles and granular permissions
- Spatie Media Library for product, category, project and general media uploads
- Zarinpal payment gateway
- Kavenegar SMS notifications
- SQLite for local/CI; production can use MySQL/PostgreSQL through Laravel configuration
- BYekan WOFF2, bundled locally and materialized to `public/fonts/BYekan.woff2` during Composer install

## Features

- Bright white/ivory luxury RTL storefront
- Product catalog, search, filters and pagination
- Database-driven multi-column mega menu
- Product gallery, specifications, inventory and SEO metadata
- Interactive rug-corner drag/peel experience
- Cart, checkout, coupon engine and automatic discount rules
- Wishlist and four-item comparison without mandatory login
- Zarinpal request/verify flow with authority validation and idempotent order finalization
- Kavenegar order notification support
- Project/portfolio CMS with cover and gallery media
- Customer CRM summary generated from order history
- Dynamic XML sitemap and structured data for products/projects
- Professional bright admin panel
- Roles: `super-admin`, `manager`, `editor`
- Granular permissions for catalog, orders, customers, projects, discounts, media, users, menus, reports and settings
- Media manager with upload/delete support
- Encrypted gateway/SMS secrets in CMS settings
- Sales reports, top products and low-stock warnings

## Installation

```bash
git clone https://github.com/mohsenmojadam2019/carpet-company.git
cd carpet-company
git checkout feat/luxury-carpet-commerce
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

`composer install` automatically reconstructs the bundled BYekan WOFF2 into `public/fonts/BYekan.woff2`.

## Admin account

Set these values in `.env` before running the seeder:

```dotenv
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=use-a-strong-password
```

Then run:

```bash
php artisan db:seed
```

Admin login: `/admin/login`

No production admin password is committed to the repository.

## Zarinpal and Kavenegar

You may configure credentials through environment variables or, after the first admin login, from **Admin → Settings**. Secrets entered in the admin panel are encrypted using Laravel's application key and are not displayed back in plaintext.

```dotenv
ZARINPAL_MERCHANT_ID=
ZARINPAL_SANDBOX=true
ZARINPAL_CALLBACK_URL="${APP_URL}/payment/zarinpal/callback"
ZARINPAL_AMOUNT_MULTIPLIER=10

KAVENEGAR_API_KEY=
KAVENEGAR_SENDER=
KAVENEGAR_VERIFY_TEMPLATE=
```

The default store price unit is toman; the configured multiplier converts the amount sent to the gateway when required.

## Media

Uploads use the Laravel `public` disk. Ensure the storage symlink exists in deployment:

```bash
php artisan storage:link
```

Supported admin media includes JPG, PNG, WebP and PDF in the general media manager. Product/category/project collections accept optimized web images.

## Production deploy

A typical deployment should run:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
php artisan migrate --force
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Point the web server document root to `public/`, enable HTTPS, compression and long-lived caching for versioned/static assets.

## Tests and CI

```bash
php artisan test
```

GitHub Actions validates PHP 8.4, Composer installation, application boot, migrations, seeders, route/config/view caching, Spatie permissions, Media Library, admin pages, CMS settings, storefront routes and the bundled BYekan font.

## Performance and SEO

The UI avoids a heavy frontend framework, uses semantic Blade markup, local fonts, reduced-motion support, lazy loading for non-critical images, canonical metadata, Open Graph metadata, Product/CreativeWork JSON-LD and a dynamic sitemap. Lighthouse scores must be measured against the deployed environment rather than assumed from source code.

## License

MIT.
