# فهرست کامل امکانات — Carpet Company / خانه فرش

این سند قابلیت‌های پیاده‌سازی‌شده در شاخه `feat/luxury-carpet-commerce` را مستند می‌کند.

## مرجع تصویری صفحه محصول

![مرجع صفحه محصول و Room Preview](images/product-page-reference.webp)

این تصویر مرجع صفحه محصول نهایی است و ترکیب گالری محصول، Rug Corner interaction، انتخاب سایز، متریال، موجودی، افزودن به سبد، Wishlist، خدمات تحویل و Room Preview را مشخص می‌کند.

## تجربه کاربری و رابط فروشگاه

- رابط کاملاً RTL و فارسی با فونت BYekan WOFF2.
- Design System روشن با White / Ivory / Champagne و بدون تم تیره.
- طراحی responsive برای دسکتاپ، تبلت و موبایل.
- Header چسبان، منوی اصلی و Mega Menu دیتابیس‌محور.
- Hero ادیتوریال، کالکشن‌ها، محصولات شاخص، پروژه‌ها و بخش مشاوره.
- Parallax سبک و Reveal animation با احترام به `prefers-reduced-motion`.
- تعامل اختصاصی فرش: گرفتن گوشه فرش با Mouse / Touch / Keyboard.
- موج طبیعی گوشه فرش در hover، بلندشدن لبه با Perspective، پشتِ تاخورده، سایه لبه و برگشت Spring بعد از رها کردن.
- دسترسی‌پذیری پایه شامل skip-link، aria-label، keyboard interaction و reduced motion.

## کاتالوگ و محصول

- دسته‌بندی چندگانه محصولات.
- جست‌وجو بر اساس نام، SKU، متریال و مبدأ.
- فیلتر دسته‌بندی، حداقل/حداکثر قیمت و مرتب‌سازی قیمت/تاریخ.
- Pagination کاتالوگ.
- صفحه محصول با نام، SKU، قیمت، قیمت نهایی، موجودی، ابعاد، متریال، نوع بافت، تراکم و مبدأ.
- گالری محصول از Spatie Media Library با fallback به تصاویر legacy.
- انتخاب و نمایش گزینه‌های سایز/ابعاد و اطلاعات متریال در Product UI.
- Room Preview برای نمایش فرش در فضای واقعی/دکوراسیون.
- وضعیت موجودی و Delivery & Services در صفحه محصول.
- محصولات مرتبط بر اساس دسته‌بندی.
- Wishlist مبتنی بر Session.
- Compare مبتنی بر Session تا ۴ محصول.
- Cart مبتنی بر Session با تغییر تعداد و حذف محصول.

## قیمت‌گذاری و تخفیف

- `PricingService` مرکزی برای محاسبه قیمت.
- Sale price در سطح محصول.
- Discount Rule با اولویت.
- Scope تخفیف برای همه محصولات، محصول مشخص یا دسته‌بندی مشخص.
- تخفیف درصدی و مبلغ ثابت.
- Coupon با حداقل سفارش، سقف تخفیف، محدودیت مصرف و بازه زمانی.
- افزایش `used_count` کوپن فقط پس از Verify موفق پرداخت.

## Checkout، پرداخت و فاکتور

- Checkout با اطلاعات نام، موبایل، ایمیل، استان، شهر، آدرس، کدپستی و یادداشت.
- محاسبه subtotal، discount، shipping و total.
- ایجاد Order و OrderItem داخل transaction دیتابیس.
- کنترل موجودی هنگام ایجاد سفارش.
- اتصال زرین‌پال با Request و Verify.
- Sandbox mode برای توسعه و تست.
- تطبیق Authority callback با سفارش ذخیره‌شده.
- جلوگیری از پردازش دوباره callback پرداخت‌شده.
- کم‌کردن موجودی داخل transaction بعد از Verify موفق.
- انتقال سفارش به `inventory_review` در صورت تغییر موجودی حین پرداخت.
- Payment reference و زمان پرداخت.
- شماره فاکتور یکتا پس از پرداخت موفق.
- Public token غیرقابل حدس برای لینک عمومی سفارش/فاکتور.
- صفحه تأیید سفارش.
- فاکتور قابل چاپ با اقلام، قیمت واحد، تخفیف، ارسال، مبلغ نهایی و Ref ID.

## چرخه سفارش و Fulfillment

- وضعیت‌های `pending`, `processing`, `inventory_review`, `packed`, `shipped`, `completed`, `cancelled`, `refunded`.
- زمان‌های `paid_at`, `shipped_at`, `completed_at`, `cancelled_at`.
- Order Status History / Audit Trail.
- ثبت وضعیت قبلی، وضعیت جدید، یادداشت و کاربر تغییر‌دهنده.
- مدیریت وضعیت سفارش از پنل ادمین.
- نمایش فاکتور مستقیم از جزئیات سفارش ادمین.

## پیامک کاوه‌نگار

- سرویس Kavenegar مستقل.
- ارسال SMS عادی.
- پشتیبانی Verify Lookup.
- Timeout و Retry برای درخواست‌های خارجی.
- Sandbox mode که تمام SMSهای خروجی را suppress می‌کند.
- API Key قابل ذخیره به‌صورت encrypted در Settings.
- Sender و Verify Template قابل مدیریت از پنل.

## Spatie Media Library

- `spatie/laravel-medialibrary` برای مدیریت فایل و تصویر.
- Collection گالری محصول.
- تصویر دسته‌بندی.
- Cover و Gallery پروژه‌ها.
- Media Manager مستقل در پنل.
- Upload و Delete مدیا.
- ذخیره فایل‌ها روی public disk و استفاده از `storage:link`.

## Spatie Permission

- `spatie/laravel-permission` برای Role / Permission.
- نقش‌های پیش‌فرض `super-admin`, `manager`, `editor`.
- Permissionهای granular برای admin access، محصول، دسته‌بندی، سفارش، مشتری، پروژه، تخفیف، مدیا، کاربر، تنظیمات، منو و گزارش‌ها.
- جلوگیری از ورود کاربر authenticated بدون `admin.access` با HTTP 403.
- هدایت Guest به صفحه Login مدیریت.
- UI مدیریت User / Role / Permission.

## پنل مدیریت

- Admin Design System مستقل و کاملاً روشن.
- Sidebar responsive با Mobile Drawer.
- Topbar و وضعیت Sandbox.
- Dashboard KPI برای فروش، سفارش، مشتری، محصول و Low Stock.
- نمودار فروش ۱۴ روزه.
- Order pipeline.
- آخرین سفارش‌ها.
- پرفروش‌ترین محصولات.
- هشدار موجودی پایین.
- CRUD محصولات.
- CRUD دسته‌بندی‌ها.
- مدیریت سفارش‌ها.
- CRM سبک مشتریان بر اساس سفارش‌ها.
- CRUD پروژه‌ها.
- CRUD کدهای تخفیف.
- CRUD قوانین تخفیف.
- Media Manager.
- مدیریت Mega Menu.
- مدیریت Users و Roles.
- Reports.
- Store / SEO / Payment / SMS Settings.

## CMS و پروژه‌ها

- پروژه/نمونه‌کار با عنوان، slug، موقعیت، سال، خلاصه و متن کامل.
- Cover و Gallery با Media Library.
- پروژه‌های Featured.
- صفحات عمومی لیست و جزئیات پروژه.
- Mega Menu قابل مدیریت از دیتابیس.
- Store Settings برای نام برند، شعار، تلفن، آدرس و Instagram.

## SEO و Performance

- Semantic HTML پایه.
- Meta title و meta description قابل تنظیم.
- Canonical URL.
- OpenGraph type/title/description/image.
- Product JSON-LD با قیمت، currency، availability و URL.
- Store JSON-LD.
- Sitemap XML پویا برای Home، Catalog، Projects و Products.
- `robots` مناسب صفحات عمومی و `noindex` پنل/فاکتور.
- تصاویر lazy-load به‌جز تصاویر مهم LCP.
- عرض و ارتفاع مشخص تصاویر برای کاهش CLS.
- BYekan به‌صورت WOFF2 self-hosted با `font-display: swap`.
- JS سبک و Vanilla؛ بدون framework سنگین فرانت.
- `prefers-reduced-motion` برای محدودکردن motion.
- قابلیت config / route / view cache در deployment.

## Seeder دمو

`php artisan migrate --seed` در محیط non-production ایجاد می‌کند:

- ۸ دسته اصلی.
- حداقل ۴۰ محصول، ۵ محصول در هر دسته.
- قالی دستباف، فرش ماشینی، تابلوفرش، موکت، موکت‌تایل، قالیچه/فرشینه، رانر و گلیم.
- ۵ پروژه نمونه.
- ۲ Coupon نمونه.
- حداقل ۱۲ سفارش نمونه با وضعیت‌های مختلف.
- مشتری‌های نمونه برای CRM و Dashboard.
- Mega Menu اولیه.
- Settings پایه و Sandbox روشن.
- Super Admin و Manager دمو فقط در local/testing.

## امنیت و Configuration

- Credential واقعی زرین‌پال و کاوه‌نگار داخل Repository commit نمی‌شود.
- Merchant ID و API Key از ENV یا Encrypted Settings دریافت می‌شوند.
- مقدار encrypted دوباره به‌صورت plaintext در فرم نمایش داده نمی‌شود.
- Demo admin در production ساخته نمی‌شود.
- CSRF برای فرم‌های state-changing.
- Permission gate برای عملیات پنل.
- Public order/invoice URLs با token غیرقابل حدس.
- transaction و lock برای بخش‌های حساس موجودی/پرداخت.

## تست و CI

- GitHub Actions روی PHP 8.4.
- Composer install و package discovery.
- Laravel boot و `route:list`.
- Migration و Seeder روی SQLite تمیز.
- `storage:link`.
- `config:cache`, `route:cache`, `view:cache`.
- Storefront smoke tests.
- Admin permission tests.
- Spatie Media Library attachment test.
- encrypted settings round-trip test.
- Sitemap / Project / Wishlist / Compare render tests.
- اعتبارسنجی فایل BYekan WOFF2 از filesystem.
- E2E با HTTP Fake: Checkout → Zarinpal request → Callback → Verify → Stock → Coupon → Invoice → Admin status transition.
- در CI هیچ پرداخت واقعی و هیچ SMS واقعی انجام نمی‌شود.
