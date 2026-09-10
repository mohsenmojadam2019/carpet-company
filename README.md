# Carpet Company — خانه فرش

Luxury Persian carpet ecommerce, corporate presentation and project showcase built with **Laravel 13 / PHP 8.4 / Blade**, **Spatie Media Library** and **Spatie Permission**.

> طراحی پروژه عمداً کاملاً روشن است: White / Ivory / Champagne، فونت BYekan، RTL، تایپوگرافی مینیمال و بدون داشبورد تیره یا ظاهر قالب آماده.

## Product Experience Concept

![Product page concept with interactive rug corner and room preview](docs/images/product-page-concept.svg)

در تجربه محصول، گوشه فرش فقط یک تصویر تزئینی نیست. کاربر می‌تواند با Mouse، Touch یا Keyboard گوشه را بگیرد؛ لبه فرش با **موج نرم، Perspective، پشتِ تاخورده و سایه واقعی‌تر** بالا می‌آید، طرح زیرین نمایان می‌شود و پس از رها کردن با حرکت Spring برمی‌گردد.

## قابلیت‌های اصلی

- فروشگاه RTL و responsive با BYekan WOFF2.
- Home ادیتوریال، Hero، کالکشن، محصولات شاخص، پروژه‌ها و مشاوره.
- Mega Menu دیتابیس‌محور.
- کاتالوگ با Search، Category filter، Price range، Sort و Pagination.
- صفحه محصول با گالری، مشخصات، موجودی، قیمت، محصولات مرتبط و Product JSON-LD.
- تعامل اختصاصی **Rug Corner Wave & Lift** با Pointer/Touch/Keyboard و `prefers-reduced-motion`.
- Session Cart، Wishlist و Compare.
- PricingService، Sale Price، Discount Rules و Coupon.
- Checkout کامل و اتصال زرین‌پال با Sandbox، Request، Verify و callback idempotent.
- کنترل transactional موجودی و Coupon usage پس از Verify موفق.
- Order lifecycle، Audit Trail، public token و printable invoice.
- Kavenegar SMS / Verify Lookup با Retry/Timeout و Sandbox suppress mode.
- Spatie Media Library برای Product Gallery، Category Cover، Project Cover/Gallery و Media Manager.
- Spatie Permission برای `super-admin`, `manager`, `editor` و دسترسی‌های granular.
- Admin Panel روشن و responsive با KPI، نمودار ۱۴ روزه، pipeline سفارش، low-stock، CRUDها، CRM، Reports، Settings و Mega Menu Manager.
- CMS پروژه‌ها و نمونه‌کارها.
- SEO شامل canonical، OpenGraph، JSON-LD و Dynamic Sitemap.
- GitHub Actions روی PHP 8.4 با migration/seed/cache/tests و E2E پرداخت Fake.

**فهرست جزئی و کامل تمام امکانات:** [docs/FEATURES.md](docs/FEATURES.md)

## Demo login

فقط در local/testing و وقتی `APP_ENV` برابر production نیست:

- Super Admin: `admin@carpet.local` / `CarpetAdmin1405!`
- Manager: `manager@carpet.local` / `CarpetManager1405!`

در production هیچ حساب demo ساخته نمی‌شود. برای محیط production از `ADMIN_EMAIL` و `ADMIN_PASSWORD` در environment استفاده کنید.

## Demo catalog / Seeder

```bash
php artisan migrate --seed
```

Seeder حداقل این داده‌ها را می‌سازد:

- ۸ خانواده محصول.
- ۴۰ محصول؛ حداقل ۵ محصول در هر خانواده.
- قالی دستباف، فرش ماشینی، تابلوفرش، موکت، موکت‌تایل/موکت‌فرش، قالیچه/فرشینه، رانر و گلیم.
- ۵ پروژه نمونه.
- ۲ کوپن نمونه.
- ۱۲ سفارش نمونه با وضعیت‌های pending / processing / inventory_review / packed / shipped / completed / cancelled.
- مشتری‌ها، Mega Menu و Settings اولیه.

## Payment / SMS configuration

هیچ Merchant ID واقعی زرین‌پال یا API Key واقعی کاوه‌نگار در repository عمومی commit نمی‌شود. حالت امن پیش‌فرض Sandbox است:

```env
ZARINPAL_MERCHANT_ID=
ZARINPAL_SANDBOX=true
ZARINPAL_CALLBACK_URL="${APP_URL}/payment/zarinpal/callback"

KAVENEGAR_API_KEY=
KAVENEGAR_SENDER=
KAVENEGAR_VERIFY_TEMPLATE=
KAVENEGAR_SANDBOX=true
```

Credentialها از ENV یا Encrypted Settings پنل دریافت می‌شوند. مقدارهای رمزنگاری‌شده دوباره به‌صورت plaintext داخل فرم نمایش داده نمی‌شوند. وقتی `KAVENEGAR_SANDBOX=true` باشد هیچ SMS خارجی ارسال نمی‌شود.

## Order lifecycle & invoice

سفارش پس از پرداخت موفق دارای شماره فاکتور یکتا و public token غیرقابل حدس می‌شود. چرخه سفارش شامل payment reference، transactional stock decrement، coupon accounting، invoice، صفحه تأیید سفارش، زمان‌های ارسال/تکمیل/لغو و Audit Trail تغییر وضعیت است.

وضعیت‌های پشتیبانی‌شده:

`pending → processing → inventory_review / packed → shipped → completed`

همچنین `cancelled` و `refunded` پشتیبانی می‌شوند.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

BYekan از source chunks پروژه به `public/fonts/BYekan.woff2` materialize می‌شود.

## Quality checks

```bash
php artisan route:list
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan test
```

Test suite شامل Storefront smoke tests، Permission، Media Library، encrypted settings، Sitemap، Seeder گسترده و E2E کامل زیر است:

`Checkout → Zarinpal Sandbox Request → Callback → Verify → Stock → Coupon → Invoice → Admin Order Transition`

تمام callهای خارجی در CI Fake/Sandbox هستند؛ بنابراین هیچ پرداخت واقعی یا SMS واقعی در تست انجام نمی‌شود.
