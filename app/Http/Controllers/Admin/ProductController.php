<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $this->guard('products.view');
        $products = Product::with(['category','media'])
            ->when($request->filled('q'), fn($q) => $q->where(fn($n) => $n->where('name','like','%'.$request->string('q').'%')->orWhere('sku','like','%'.$request->string('q').'%')))
            ->when($request->filled('category'), fn($q) => $q->where('category_id',$request->integer('category')))
            ->latest()->paginate(20)->withQueryString();
        return view('admin.products.index',['products'=>$products,'categories'=>Category::orderBy('name')->get()]);
    }
    public function create(): View { $this->guard('products.manage'); return view('admin.products.form',['product'=>new Product(),'categories'=>Category::where('is_active',true)->orderBy('name')->get()]); }
    public function store(Request $request): RedirectResponse { $this->guard('products.manage'); $product=Product::create($this->payload($request)); $this->syncMedia($request,$product); return redirect()->route('admin.products.edit',$product)->with('success','محصول ایجاد شد.'); }
    public function edit(Product $product): View { $this->guard('products.manage'); $product->load('media'); return view('admin.products.form',['product'=>$product,'categories'=>Category::orderBy('name')->get()]); }
    public function update(Request $request, Product $product): RedirectResponse { $this->guard('products.manage'); $product->update($this->payload($request,$product)); $this->syncMedia($request,$product); return back()->with('success','محصول به‌روزرسانی شد.'); }
    public function destroy(Product $product): RedirectResponse { $this->guard('products.manage'); $product->update(['is_active'=>false]); return back()->with('success','محصول غیرفعال شد.'); }

    private function syncMedia(Request $request, Product $product): void
    {
        foreach ($request->file('gallery',[]) as $file) $product->addMedia($file)->usingName(pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME))->toMediaCollection('gallery');
        foreach ((array)$request->input('remove_media',[]) as $id) $product->media()->whereKey((int)$id)->first()?->delete();
    }

    private function payload(Request $request, ?Product $product=null): array
    {
        $data=$request->validate([
            'category_id'=>['required','exists:categories,id'],'name'=>['required','string','max:190'],'slug'=>['nullable','string','max:190'],
            'sku'=>['required','string','max:80','unique:products,sku'.($product?','.$product->id:'')],'short_description'=>['nullable','string','max:500'],'description'=>['nullable','string'],
            'price'=>['required','integer','min:0'],'sale_price'=>['nullable','integer','min:0'],'stock'=>['required','integer','min:0'],'width'=>['nullable','numeric','min:0'],'height'=>['nullable','numeric','min:0'],
            'material'=>['nullable','string','max:100'],'weave'=>['nullable','string','max:100'],'density'=>['nullable','string','max:100'],'origin'=>['nullable','string','max:100'],'colors_text'=>['nullable','string'],
            'meta_title'=>['nullable','string','max:190'],'meta_description'=>['nullable','string','max:320'],'gallery'=>['nullable','array','max:12'],'gallery.*'=>['image','mimes:jpg,jpeg,png,webp','max:8192'],'remove_media'=>['nullable','array'],'remove_media.*'=>['integer']
        ]);
        $slug=$data['slug']?:Str::slug($data['name']); if($slug==='')$slug='product-'.Str::lower(Str::random(8)); $base=$slug;$counter=2;
        while(Product::where('slug',$slug)->when($product,fn($q)=>$q->whereKeyNot($product->id))->exists())$slug=$base.'-'.$counter++;
        $colors=collect(explode(',',(string)($data['colors_text']??'')))->map('trim')->filter()->values()->all(); unset($data['colors_text'],$data['gallery'],$data['remove_media']);
        return $data+['slug'=>$slug,'colors'=>$colors,'images'=>$product?->images?:['/images/rug-01.svg'],'variants'=>$product?->variants?:[],'specifications'=>$product?->specifications?:[],'featured'=>$request->boolean('featured'),'is_active'=>$request->boolean('is_active',true)];
    }
    private function guard(string $permission): void { abort_unless(request()->user()?->can($permission),403); }
}
