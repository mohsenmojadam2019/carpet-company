<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\MenuItem;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $permissions = ['admin.access','products.view','products.manage','categories.view','categories.manage','orders.view','orders.manage','customers.view','projects.view','projects.manage','discounts.view','discounts.manage','media.view','media.manage','users.view','users.manage','settings.view','settings.manage','menus.view','menus.manage','reports.view'];
        foreach ($permissions as $permission) Permission::findOrCreate($permission, 'web');

        $superAdmin = Role::findOrCreate('super-admin', 'web');
        $superAdmin->syncPermissions(Permission::all());
        $manager = Role::findOrCreate('manager', 'web');
        $manager->syncPermissions(['admin.access','products.view','products.manage','categories.view','categories.manage','orders.view','orders.manage','customers.view','projects.view','projects.manage','discounts.view','discounts.manage','media.view','media.manage','settings.view','settings.manage','menus.view','menus.manage','reports.view']);
        $editor = Role::findOrCreate('editor', 'web');
        $editor->syncPermissions(['admin.access','products.view','products.manage','categories.view','categories.manage','projects.view','projects.manage','media.view','media.manage']);

        if (env('ADMIN_EMAIL') && env('ADMIN_PASSWORD')) {
            $admin = User::updateOrCreate(['email'=>env('ADMIN_EMAIL')], ['name'=>'مدیر فروشگاه','password'=>Hash::make((string) env('ADMIN_PASSWORD')),'is_admin'=>true]);
            $admin->syncRoles([$superAdmin]);
        }

        $categories = collect([
            ['name'=>'قالی دستباف','slug'=>'handmade','description'=>'قالی‌های اصیل، انتخاب‌شده و مناسب فضاهای ماندگار.'],
            ['name'=>'فرش ماشینی','slug'=>'machine-made','description'=>'طراحی معاصر، نگهداری ساده و تنوع ابعاد.'],
            ['name'=>'تابلو فرش','slug'=>'wall-rug','description'=>'بافت هنری برای دیوارهای شاخص.'],
            ['name'=>'موکت و موکت‌فرش','slug'=>'moquette','description'=>'راهکار حرفه‌ای برای پروژه‌های مسکونی و تجاری.'],
            ['name'=>'فرشینه و قالیچه','slug'=>'rug','description'=>'سبک، منعطف و مناسب فضاهای کوچک.'],
        ])->map(fn (array $row, int $index) => Category::updateOrCreate(['slug'=>$row['slug']], $row + ['sort_order'=>$index+1,'is_active'=>true]))->keyBy('slug');

        $products = [
            ['category'=>'handmade','name'=>'قالی روشن آریانا','slug'=>'ariana-ivory-rug','sku'=>'CH-1001','price'=>49800000,'sale_price'=>null,'stock'=>4,'width'=>200,'height'=>300,'material'=>'پشم و ابریشم','weave'=>'دستباف','density'=>'ریز بافت','origin'=>'تبریز'],
            ['category'=>'rug','name'=>'قالیچه ماهور','slug'=>'mahoor-rug','sku'=>'CH-1002','price'=>27400000,'sale_price'=>25900000,'stock'=>7,'width'=>150,'height'=>225,'material'=>'پشم','weave'=>'دستباف','density'=>'متوسط','origin'=>'کاشان'],
            ['category'=>'machine-made','name'=>'فرش هندسی سپیدار','slug'=>'sepidar-geometric','sku'=>'CH-1003','price'=>32900000,'sale_price'=>null,'stock'=>9,'width'=>200,'height'=>300,'material'=>'اکریلیک هیت‌ست','weave'=>'ماشینی','density'=>'۱۲۰۰ شانه','origin'=>'ایران'],
            ['category'=>'wall-rug','name'=>'تابلو فرش باغ ایرانی','slug'=>'persian-garden-wall-rug','sku'=>'CH-1004','price'=>18600000,'sale_price'=>null,'stock'=>3,'width'=>80,'height'=>120,'material'=>'ابریشم مصنوعی','weave'=>'ریز بافت','density'=>'بالا','origin'=>'قم'],
            ['category'=>'moquette','name'=>'موکت‌فرش لینن','slug'=>'linen-moquette','sku'=>'CH-1005','price'=>8900000,'sale_price'=>null,'stock'=>18,'width'=>300,'height'=>400,'material'=>'پلی‌آمید','weave'=>'بافت لوپ','density'=>'پروژه‌ای','origin'=>'ایران'],
            ['category'=>'handmade','name'=>'قالی کرم هریس','slug'=>'heriz-cream-rug','sku'=>'CH-1006','price'=>68500000,'sale_price'=>64900000,'stock'=>2,'width'=>250,'height'=>350,'material'=>'پشم دست‌ریس','weave'=>'دستباف','density'=>'ریز بافت','origin'=>'هریس'],
        ];
        foreach ($products as $index => $row) {
            $category = $categories->get($row['category']);
            Product::updateOrCreate(['sku'=>$row['sku']], ['category_id'=>$category->id,'name'=>$row['name'],'slug'=>$row['slug'],'short_description'=>'پالت روشن و خنثی با بافت دقیق؛ مناسب دکوراسیون مدرن، مینیمال و نئوکلاسیک.','description'=>'این محصول با تمرکز بر تناسب رنگ، کیفیت بافت و دوام انتخاب شده است. پیش از خرید می‌توانید برای انتخاب ابعاد و هماهنگی با فضای خانه مشاوره دریافت کنید.','price'=>$row['price'],'sale_price'=>$row['sale_price'],'stock'=>$row['stock'],'width'=>$row['width'],'height'=>$row['height'],'material'=>$row['material'],'weave'=>$row['weave'],'density'=>$row['density'],'origin'=>$row['origin'],'colors'=>['کرم','شیری','خاکی'],'images'=>['/images/rug-01.svg'],'variants'=>[],'specifications'=>['washable'=>true,'consultation'=>true],'featured'=>$index<4,'is_active'=>true,'meta_title'=>$row['name'].' | خرید فرش و قالی','meta_description'=>'مشاهده مشخصات، قیمت، ابعاد و خرید '.$row['name'].' با ارسال تخصصی و پرداخت امن.']);
        }

        Coupon::updateOrCreate(['code'=>'WELCOME10'], ['type'=>'percent','value'=>10,'min_order'=>10000000,'max_discount'=>5000000,'usage_limit'=>200,'used_count'=>0,'starts_at'=>now()->subDay(),'ends_at'=>now()->addMonths(3),'is_active'=>true]);
        foreach ([['title'=>'ویلای روشن لواسان','slug'=>'lavasan-light-villa','location'=>'لواسان','excerpt'=>'پالت کرم و خاکی با قالی مرکزی در نشیمن اصلی.'],['title'=>'سوئیت بوتیک تهران','slug'=>'tehran-boutique-suite','location'=>'تهران','excerpt'=>'بافت مینیمال برای فضای جمع‌وجور و نور طبیعی.'],['title'=>'لابی اقامتگاه کویر','slug'=>'desert-residence-lobby','location'=>'یزد','excerpt'=>'ترکیب قالی ایرانی با خطوط معماری معاصر.']] as $project) Project::updateOrCreate(['slug'=>$project['slug']], $project + ['year'=>(int) now()->year,'is_featured'=>true,'is_active'=>true]);

        foreach (['store_name'=>['خانه فرش','general'],'store_tagline'=>['فرش برای معماری ماندگار','general'],'store_phone'=>['','general'],'store_address'=>['','general'],'instagram_url'=>['','general'],'seo_default_title'=>['خانه فرش | خرید قالی، فرش و موکت','seo'],'seo_default_description'=>['فروشگاه تخصصی قالی، فرش، تابلو فرش، موکت و فرشینه با انتخاب حرفه‌ای و پرداخت امن.','seo'],'shipping_flat'=>['0','general'],'zarinpal_sandbox'=>['1','payments'],'kavenegar_sender'=>['','notifications'],'kavenegar_verify_template'=>['','notifications']] as $key => [$value,$group]) Setting::put($key,$value,$group,$key==='shipping_flat'?'integer':($key==='zarinpal_sandbox'?'boolean':'string'),!in_array($group,['payments','notifications'],true));

        $shop = MenuItem::updateOrCreate(['label'=>'فروشگاه','parent_id'=>null], ['route_name'=>'catalog.index','column'=>1,'sort_order'=>1,'is_active'=>true]);
        foreach ($categories->values() as $i => $category) MenuItem::updateOrCreate(['label'=>$category->name,'parent_id'=>$shop->id], ['url'=>'/shop?category='.$category->slug,'column'=>1+intdiv($i,3),'sort_order'=>$i+1,'is_active'=>true]);
        MenuItem::updateOrCreate(['label'=>'پروژه‌ها','parent_id'=>null], ['route_name'=>'projects.index','column'=>2,'sort_order'=>2,'is_active'=>true]);
    }
}
