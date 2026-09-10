# Carpet Company — خانه فرش

Luxury Persian carpet ecommerce and project showcase built with Laravel 13 / PHP 8.4, Blade, Spatie Media Library and Spatie Permission.

## Demo login

Only in local/testing (`APP_ENV` is not `production`):

- Super admin: `admin@carpet.local` / `CarpetAdmin1405!`
- Manager: `manager@carpet.local` / `CarpetManager1405!`

Production does not create those demo accounts. Supply `ADMIN_EMAIL` and `ADMIN_PASSWORD` explicitly in the production environment.

## Demo catalog

`php artisan migrate --seed` creates 8 families and at least 40 products: handmade rugs, machine-made carpets, wall rugs, moquette, carpet tiles, rugs, runners and kilims. It also creates projects, coupons, customers and sample orders across pending, processing, inventory-review, packed, shipped, completed and cancelled states.

## Payment / SMS configuration

No live merchant ID or SMS API key is committed to this public repository. The safe defaults are sandbox mode and environment/encrypted admin settings:

```env
ZARINPAL_MERCHANT_ID=
ZARINPAL_SANDBOX=true
KAVENEGAR_API_KEY=
KAVENEGAR_SANDBOX=true
```

Kavenegar sandbox suppresses outbound requests completely. Zarinpal sandbox uses the sandbox request/verify/start-pay endpoints. The admin settings page stores supplied credentials encrypted and never re-renders their plaintext value.

## Order lifecycle and invoice

Successful payment creates a unique invoice number and retains a non-guessable public order token. The flow includes payment reference, transactional stock decrement, coupon accounting, printable invoice, public order-confirmation page, shipping/completion timestamps and a status-history audit trail.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

BYekan is materialized to `public/fonts/BYekan.woff2` by the Composer post-update script.

## Quality checks

```bash
php artisan route:list
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan test
```

The test suite covers storefront rendering, Spatie Permission, Spatie Media Library, the broad demo seed and an end-to-end checkout → Zarinpal sandbox request → callback verification → stock/coupon accounting → invoice → admin order transition flow using HTTP fakes, so CI never performs a real payment or sends a real SMS.
