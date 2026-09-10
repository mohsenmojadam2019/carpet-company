<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Category; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request; use Illuminate\Support\Str; use Illuminate\Validation\Rule; use Illuminate\View\View;
class CategoryController extends Controller
{
    public function index(): View { $this->guard('categories.view'); return view('admin.categories.index',['categories'=>Category::withCount('products')->with('parent')->orderBy('sort_order')->get()]); }
    public function create(): View { $this->guard('categories.manage'); return view('admin.categories.form',['category'=>new Category(),'parents'=>Category::orderBy('name')->get()]); }
    public function store(Request $request): RedirectResponse { $this->guard('categories.manage'); $category=Category::create($this->payload($request)); $this->syncCover($request,$category); return redirect()->route('admin.categories.edit',$category)->with('success','دسته‌بندی ایجاد شد.'); }
    public function edit(Category $category): View { $this->guard('categories.manage'); return view('admin.categories.form',['category'=>$category,'parents'=>Category::whereKeyNot($category->id)->orderBy('name')->get()]); }
    public function update(Request $request, Category $category): RedirectResponse { $this->guard('categories.manage'); $category->update($this->payload($request,$category)); $this->syncCover($request,$category); return back()->with('success','دسته‌بندی به‌روزرسانی شد.'); }
    public function destroy(Category $category): RedirectResponse { $this->guard('categories.manage'); abort_if($category->products()->exists(),422,'ابتدا محصولات این دسته را جابه‌جا کنید.'); $category->delete(); return redirect()->route('admin.categories.index')->with('success','دسته‌بندی حذف شد.'); }
    private function syncCover(Request $request,Category $category):void { if($request->hasFile('cover'))$category->addMediaFromRequest('cover')->toMediaCollection('cover'); if($request->boolean('remove_cover'))$category->clearMediaCollection('cover'); }
    private function payload(Request $request,?Category $category=null):array { $data=$request->validate(['parent_id'=>['nullable','exists:categories,id'],'name'=>['required','string','max:190'],'slug'=>['nullable','string','max:190',Rule::unique('categories','slug')->ignore($category)],'description'=>['nullable','string'],'sort_order'=>['nullable','integer','min:0'],'cover'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:8192']]); unset($data['cover']); $slug=$data['slug']?:Str::slug($data['name']); if($slug==='')$slug='category-'.Str::lower(Str::random(8)); return $data+['slug'=>$slug,'sort_order'=>(int)($data['sort_order']??0),'is_active'=>$request->boolean('is_active',true)]; }
    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
