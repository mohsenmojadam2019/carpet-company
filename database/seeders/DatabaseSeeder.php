<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->seedAccess();
        $categories=$this->seedCatalog();
        $this->seedCommerceSamples();
        $this->seedProjects();
        $this->seedSettings();
        $this->seedMenus($categories);
    }

    private function seedAccess(): void
    {
        $permissions=['admin.access','products.view','products.manage','categories.view','categories.manage','orders.view','orders.manage','customers.view','projects.view','projects.manage','discounts.view','discounts.manage','media.view','media.manage','users.view','users.manage','settings.view','settings.manage','menus.view','menus.manage','reports.view'];
        foreach($permissions as $permission)Permission::findOrCreate($permission,'web');
        $superAdmin=Role::findOrCreate('super-admin','web');$superAdmin->syncPermissions(Permission::all());
        $manager=Role::findOrCreate('manager','web');$manager->syncPermissions(['admin.access','products.view','products.manage','categories.view','categories.manage','orders.view','orders.manage','customers.view','projects.view','projects.manage','discounts.view','discounts.manage','media.view','media.manage','settings.view','settings.manage','menus.view','menus.manage','reports.view']);
        $editor=Role::findOrCreate('editor','web');$editor->syncPermissions(['admin.access','products.view','products.manage','categories.view','categories.manage','projects.view','projects.manage','media.view','media.manage']);
        if(app()->environment('production')){
            if(env('ADMIN_EMAIL')&&env('ADMIN_PASSWORD')){$admin=User::updateOrCreate(['email'=>env('ADMIN_EMAIL')],['name'=>'مدیر ارشد فروشگاه','password'=>(string)env('ADMIN_PASSWORD'),'is_admin'=>true]);$admin->syncRoles([$superAdmin]);}
            return;
        }
        $admin=User::updateOrCreate(['email'=>'admin@carpet.local'],['name'=>'مدیر ارشد خانه فرش','phone'=>'09120000001','password'=>'CarpetAdmin1405!','is_admin'=>true]);$admin->syncRoles([$superAdmin]);
        $demoManager=User::updateOrCreate(['email'=>'manager@carpet.local'],['name'=>'مدیر فروش دمو','phone'=>'09120000002','password'=>'CarpetManager1405!','is_admin'=>true]);$demoManager->syncRoles([$manager]);
    }

    private function seedCatalog()
    {
        $categoryRows=[
            ['name'=>'قالی دستباف','slug'=>'handmade','description'=>'قالی‌های اصیل ایرانی از تبریز، قم، کاشان و هریس.'],
            ['name'=>'فرش ماشینی','slug'=>'machine-made','description'=>'فرش‌های مدرن و کلاسیک با تراکم بالا و رنگ‌بندی روشن.'],
            ['name'=>'تابلو فرش','slug'=>'wall-rug','description'=>'بافت هنری برای دیوارهای شاخص و فضاهای تشریفاتی.'],
            ['name'=>'موکت','slug'=>'moquette','description'=>'موکت مسکونی و پروژه‌ای با بافت لوپ، کات و آکوستیک.'],
            ['name'=>'موکت‌فرش و تایل','slug'=>'carpet-tile','description'=>'راهکار مدولار برای دفتر، هتل، لابی و پروژه‌های پرتردد.'],
            ['name'=>'فرشینه و قالیچه','slug'=>'rug','description'=>'قالیچه و فرشینه سبک برای نشیمن، اتاق و فضاهای کوچک.'],
            ['name'=>'کناره و رانر','slug'=>'runner','description'=>'رانرهای باریک برای راهرو، ورودی، کنار تخت و آشپزخانه.'],
            ['name'=>'گلیم و بافت تخت','slug'=>'kilim','description'=>'گلیم، جاجیم و بافت تخت با روح معاصر و اصالت ایرانی.'],
        ];
        $categories=collect($categoryRows)->map(fn(array $row,int $i)=>Category::updateOrCreate(['slug'=>$row['slug']],$row+['sort_order'=>$i+1,'is_active'=>true]))->keyBy('slug');
        $catalog=[
            'handmade'=>[['قالی آریانا روشن','ariana-ivory-handmade',89000000,'پشم و ابریشم','دستباف','رج ۵۰','تبریز',200,300,4],['قالی هریس شامپاینی','heriz-champagne',128000000,'پشم دست‌ریس','دستباف','ریز بافت','هریس',250,350,2],['قالی افشان مهتاب','mahtab-afshan',176000000,'ابریشم و کرک','دستباف','رج ۶۰','قم',200,300,1],['قالی خشتی کاشان','kashan-kheshti',74000000,'پشم مرینوس','دستباف','رج ۴۵','کاشان',200,300,3],['قالی ماهی نقره‌ای','silver-mahi-tabriz',149000000,'کرک و ابریشم','دستباف','رج ۵۵','تبریز',250,350,2]],
            'machine-made'=>[['فرش سپیدار ۱۲۰۰ شانه','sepidar-1200',32900000,'اکریلیک هیت‌ست','ماشینی','۱۲۰۰ شانه','کاشان',200,300,12],['فرش آرتا مینیمال','arta-minimal',28600000,'اکریلیک هیت‌ست','ماشینی','۱۰۰۰ شانه','ایران',200,300,15],['فرش نیلا وینتیج','nila-vintage',36500000,'اکریلیک و پلی‌استر','ماشینی','۱۲۰۰ شانه','کاشان',250,350,8],['فرش روناک کرم','ronak-cream',21800000,'پلی‌استر نرم','ماشینی','۷۰۰ شانه','مشهد',200,300,20],['فرش لوتوس نئوکلاسیک','lotus-neoclassic',41900000,'اکریلیک هیت‌ست','ماشینی','۱۵۰۰ شانه','کاشان',300,400,6]],
            'wall-rug'=>[['تابلو فرش باغ ایرانی','persian-garden-wall-rug',18600000,'ابریشم مصنوعی','ریز بافت','بالا','قم',80,120,5],['تابلو فرش دروازه نور','gate-of-light-wall-rug',24900000,'ابریشم','دستباف','رج ۶۰','قم',70,100,3],['تابلو فرش سرو و ماه','cypress-moon-wall-rug',15800000,'کرک و ابریشم','دستباف','ریز بافت','تبریز',60,90,4],['تابلو فرش کوچه باغ','garden-alley-wall-rug',13200000,'پلی‌استر ابریشمی','ماشینی','۱۲۰۰ شانه','کاشان',70,100,8],['تابلو فرش هندسه ایرانی','persian-geometry-wall-rug',21900000,'ابریشم مصنوعی','ریز بافت','بالا','قم',90,120,2]],
            'moquette'=>[['موکت لینن صدفی','linen-pearl-moquette',8900000,'پلی‌آمید','لوپ','پروژه‌ای','ایران',300,400,30],['موکت هتل آکوستیک','hotel-acoustic-moquette',12600000,'نایلون مقاوم','کات پایل','تجاری','ایران',300,400,22],['موکت ولور کرم','velour-cream-moquette',9800000,'پلی‌پروپیلن','ولور','مسکونی','ایران',300,400,28],['موکت ریب بافت سنگی','rib-stone-moquette',7600000,'پلی‌آمید','ریب','پرتردد','ایران',300,400,35],['موکت لوپ شامپاینی','champagne-loop-moquette',11200000,'نایلون','لوپ چندسطحی','پروژه‌ای','ایران',300,400,24]],
            'carpet-tile'=>[['موکت تایل آکوستیک استودیو','studio-acoustic-tile',2450000,'نایلون','تایل لوپ','کلاس ۳۳','ایران',50,50,120],['موکت تایل هتل گریژ','hotel-greige-tile',2680000,'پلی‌آمید','تایل لوپ','کلاس ۳۳','ایران',50,50,140],['موکت‌فرش مدولار آریو','ario-modular-carpet',3190000,'نایلون','مدولار','پرتردد','ایران',50,50,90],['تایل خطی سند','sand-line-carpet-tile',2290000,'پلی‌پروپیلن','تایل','اداری','ایران',50,50,160],['تایل بافت لوکس لابی','lobby-luxe-carpet-tile',3490000,'نایلون Solution Dyed','تایل','هتلی','ایران',50,50,80]],
            'rug'=>[['قالیچه ماهور','mahoor-rug',27400000,'پشم','دستباف','متوسط','کاشان',150,225,7],['فرشینه ابری روشن','cloud-ivory-rug',7900000,'مخمل چاپی','دیجیتال','سبک','ایران',150,220,18],['قالیچه ترنج مینیمال','minimal-medallion-rug',19800000,'پشم','دستباف','ریز','کرمان',120,180,6],['فرشینه موج شنی','sand-wave-rug',9400000,'مخمل و لاتکس','چاپی','متراکم','ایران',160,230,14],['قالیچه آفتاب کرم','cream-sun-rug',23500000,'پشم و ویسکوز','بافت دستی','متوسط','ایران',140,200,5]],
            'runner'=>[['کناره هریس روشن','light-heriz-runner',28900000,'پشم','دستباف','ریز','هریس',80,300,4],['رانر هندسی سپید','white-geometry-runner',6900000,'پلی‌استر','ماشینی','۷۰۰ شانه','ایران',80,250,16],['کناره ماهی تبریز','tabriz-mahi-runner',42000000,'پشم و ابریشم','دستباف','رج ۵۰','تبریز',90,350,2],['رانر لینن خاکی','linen-taupe-runner',8200000,'پلی‌آمید','بافت تخت','پروژه‌ای','ایران',80,300,13],['کناره افشان شیری','ivory-afshan-runner',15900000,'اکریلیک هیت‌ست','ماشینی','۱۲۰۰ شانه','کاشان',100,300,9]],
            'kilim'=>[['گلیم سنندج خاکی','sanandaj-taupe-kilim',22500000,'پشم طبیعی','بافت تخت','دست‌ریس','سنندج',150,220,5],['گلیم سیرجان سفید','sirjan-white-kilim',19800000,'پشم','شیریکی پیچ','دستباف','سیرجان',140,210,4],['گلیم راه‌راه مینیمال','minimal-stripe-kilim',11800000,'پشم و پنبه','بافت تخت','متوسط','ایران',160,230,10],['جاجیم شامپاینی','champagne-jajim',8700000,'پشم و پنبه','جاجیم','سبک','اردبیل',140,200,12],['گلیم هندسی کرم','cream-geometry-kilim',14600000,'پشم طبیعی','بافت تخت','دستباف','سنندج',170,240,7]],
        ];
        $sku=2001;$globalIndex=0;
        foreach($catalog as $categorySlug=>$items)foreach($items as $item){[$name,$slug,$price,$material,$weave,$density,$origin,$width,$height,$stock]=$item;$sale=$globalIndex%6===2?(int)round($price*.93):null;Product::updateOrCreate(['sku'=>'CH-'.$sku++],['category_id'=>$categories[$categorySlug]->id,'name'=>$name,'slug'=>$slug,'short_description'=>'انتخابی روشن و معاصر با متریال باکیفیت؛ مناسب خانه‌های مینیمال، مدرن و نئوکلاسیک.','description'=>'این محصول با تمرکز بر کیفیت بافت، تعادل رنگ، دوام و هماهنگی با معماری داخلی انتخاب شده است. برای انتخاب ابعاد و هماهنگی با نور و مبلمان می‌توانید پیش از خرید مشاوره دریافت کنید.','price'=>$price,'sale_price'=>$sale,'stock'=>$stock,'width'=>$width,'height'=>$height,'material'=>$material,'weave'=>$weave,'density'=>$density,'origin'=>$origin,'colors'=>['کرم','شیری','خاکی','گریژ'],'images'=>['/images/rug-01.svg'],'variants'=>[],'specifications'=>['consultation'=>true,'insured_shipping'=>true],'featured'=>$globalIndex<8,'is_active'=>true,'meta_title'=>$name.' | خانه فرش','meta_description'=>'قیمت، مشخصات، ابعاد و خرید '.$name.' با مشاوره تخصصی و ارسال بیمه‌شده.']);$globalIndex++;}
        return $categories;
    }

    private function seedCommerceSamples(): void
    {
        Coupon::updateOrCreate(['code'=>'WELCOME10'],['type'=>'percent','value'=>10,'min_order'=>10000000,'max_discount'=>5000000,'usage_limit'=>200,'used_count'=>0,'starts_at'=>now()->subDay(),'ends_at'=>now()->addMonths(3),'is_active'=>true]);
        Coupon::updateOrCreate(['code'=>'PROJECT5'],['type'=>'percent','value'=>5,'min_order'=>30000000,'max_discount'=>10000000,'usage_limit'=>100,'used_count'=>0,'starts_at'=>now()->subDay(),'ends_at'=>now()->addMonths(6),'is_active'=>true]);
        $products=Product::orderBy('id')->get();$customers=[['آرمان رستگار','09121230001','تهران','تهران'],['نیلوفر فرهمند','09121230002','تهران','لواسان'],['سامان کاویانی','09121230003','اصفهان','اصفهان'],['ترانه محسنی','09121230004','فارس','شیراز'],['امیرحسین دادفر','09121230005','خراسان رضوی','مشهد'],['مریم ساعی','09121230006','البرز','کرج']];
        $states=[['pending','pending'],['processing','paid'],['packed','paid'],['shipped','paid'],['completed','paid'],['cancelled','failed'],['inventory_review','paid'],['completed','paid'],['shipped','paid'],['processing','paid'],['packed','paid'],['pending','pending']];
        foreach($states as $i=>[$status,$payment]){$customer=$customers[$i%count($customers)];$p1=$products[$i%$products->count()];$p2=$products[($i+7)%$products->count()];$subtotal=(int)$p1->final_price+(int)$p2->final_price;$discount=$i%4===1?min(2500000,(int)round($subtotal*.05)):0;$shipping=390000;$total=$subtotal-$discount+$shipping;$number='DEMO-'.str_pad((string)($i+1),4,'0',STR_PAD_LEFT);$order=Order::firstOrNew(['order_number'=>$number]);$order->fill(['customer_name'=>$customer[0],'phone'=>$customer[1],'email'=>'customer'.($i+1).'@example.test','status'=>$status,'payment_status'=>$payment,'payment_gateway'=>'zarinpal','subtotal'=>$subtotal,'discount_amount'=>$discount,'shipping_amount'=>$shipping,'total'=>$total,'coupon_code'=>$discount?'PROJECT5':null,'shipping_address'=>['province'=>$customer[2],'city'=>$customer[3],'address'=>'خیابان نمونه، پلاک '.($i+10),'postal_code'=>'1234567890'],'notes'=>$i%3===0?'هماهنگی تلفنی قبل از ارسال.':null,'payment_authority'=>$payment==='paid'?'A-DEMO-'.($i+1):null,'payment_ref_id'=>$payment==='paid'?(string)(90800000+$i):null,'invoice_number'=>$payment==='paid'?'INV-DEMO-'.str_pad((string)($i+1),4,'0',STR_PAD_LEFT):null,'paid_at'=>$payment==='paid'?now()->subDays(12-$i):null,'shipped_at'=>in_array($status,['shipped','completed'],true)?now()->subDays(max(1,8-$i)):null,'completed_at'=>$status==='completed'?now()->subDays(max(0,5-$i)):null,'cancelled_at'=>$status==='cancelled'?now()->subDays(3):null]);$order->save();$order->items()->delete();$order->items()->createMany([['product_id'=>$p1->id,'product_name'=>$p1->name,'sku'=>$p1->sku,'quantity'=>1,'unit_price'=>$p1->final_price,'total_price'=>$p1->final_price,'meta'=>['width'=>$p1->width,'height'=>$p1->height]],['product_id'=>$p2->id,'product_name'=>$p2->name,'sku'=>$p2->sku,'quantity'=>1,'unit_price'=>$p2->final_price,'total_price'=>$p2->final_price,'meta'=>['width'=>$p2->width,'height'=>$p2->height]]]);$order->statusHistories()->delete();$steps=['pending'];if($payment==='paid')$steps[]='processing';if($status==='inventory_review')$steps[]='inventory_review';if(in_array($status,['packed','shipped','completed'],true))$steps[]='packed';if(in_array($status,['shipped','completed'],true))$steps[]='shipped';if($status==='completed')$steps[]='completed';if($status==='cancelled')$steps[]='cancelled';$from=null;foreach(array_unique($steps) as $step){$order->statusHistories()->create(['from_status'=>$from,'to_status'=>$step,'note'=>'داده نمونه برای نمایش چرخه سفارش.']);$from=$step;}}
    }

    private function seedProjects(): void
    {
        foreach([['ویلای روشن لواسان','lavasan-light-villa','لواسان','پالت کرم و خاکی با قالی مرکزی در نشیمن اصلی.'],['سوئیت بوتیک تهران','tehran-boutique-suite','تهران','بافت مینیمال برای فضای جمع‌وجور و نور طبیعی.'],['لابی اقامتگاه کویر','desert-residence-lobby','یزد','ترکیب قالی ایرانی با خطوط معماری معاصر.'],['پنت‌هاوس زعفرانیه','zaferanieh-penthouse','تهران','قالی دستباف روشن در کنار سنگ طبیعی و چوب گرم.'],['هتل بوتیک کاشان','kashan-boutique-hotel','کاشان','رانرها و موکت سفارشی برای راهرو و فضای اقامت.']] as [$title,$slug,$location,$excerpt])Project::updateOrCreate(['slug'=>$slug],['title'=>$title,'location'=>$location,'excerpt'=>$excerpt,'content'=>$excerpt.' انتخاب بافت و ابعاد با توجه به نور، مسیر حرکت و مبلمان پروژه انجام شده است.','year'=>(int)now()->year,'is_featured'=>true,'is_active'=>true]);
    }

    private function seedSettings(): void
    {
        $rows=['store_name'=>['خانه فرش','general','string',true],'store_tagline'=>['فرش برای معماری ماندگار','general','string',true],'store_phone'=>['02100000000','general','string',true],'store_address'=>['تهران، شوروم خانه فرش','general','string',true],'instagram_url'=>['','general','string',true],'seo_default_title'=>['خانه فرش | خرید قالی، فرش، موکت و گلیم','seo','string',true],'seo_default_description'=>['فروشگاه تخصصی قالی دستباف، فرش ماشینی، تابلو فرش، موکت، فرشینه، رانر و گلیم با مشاوره تخصصی و پرداخت امن.','seo','string',true],'shipping_flat'=>['390000','general','integer',true],'zarinpal_sandbox'=>['1','payments','boolean',false],'kavenegar_sandbox'=>['1','notifications','boolean',false],'kavenegar_sender'=>['','notifications','string',false],'kavenegar_verify_template'=>['verify','notifications','string',false]];foreach($rows as $key=>[$value,$group,$type,$public])Setting::put($key,$value,$group,$type,$public);
    }

    private function seedMenus($categories): void
    {
        $shop=MenuItem::updateOrCreate(['label'=>'فروشگاه','parent_id'=>null],['route_name'=>'catalog.index','column'=>1,'sort_order'=>1,'is_active'=>true]);foreach($categories->values() as $i=>$category)MenuItem::updateOrCreate(['label'=>$category->name,'parent_id'=>$shop->id],['url'=>'/shop?category='.$category->slug,'column'=>1+intdiv($i,4),'sort_order'=>$i+1,'is_active'=>true]);MenuItem::updateOrCreate(['label'=>'پروژه‌ها','parent_id'=>null],['route_name'=>'projects.index','column'=>2,'sort_order'=>2,'is_active'=>true]);
    }
}
