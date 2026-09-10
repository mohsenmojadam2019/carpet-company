<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\MenuItem; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request; use Illuminate\View\View;
class MenuItemController extends Controller
{
    public function index():View { $this->guard('menus.view'); return view('admin.menus.index',['items'=>MenuItem::with('parent')->orderBy('column')->orderBy('sort_order')->get()]); }
    public function store(Request $request):RedirectResponse { $this->guard('menus.manage'); MenuItem::create($this->payload($request)); return back()->with('success','آیتم منو اضافه شد.'); }
    public function update(Request $request,MenuItem $menu):RedirectResponse { $this->guard('menus.manage'); $menu->update($this->payload($request)); return back()->with('success','آیتم منو به‌روزرسانی شد.'); }
    public function destroy(MenuItem $menu):RedirectResponse { $this->guard('menus.manage'); $menu->delete(); return back()->with('success','آیتم منو حذف شد.'); }
    private function payload(Request $request):array { return $request->validate(['parent_id'=>['nullable','exists:menu_items,id'],'label'=>['required','string','max:100'],'url'=>['nullable','string','max:500'],'route_name'=>['nullable','string','max:100'],'column'=>['nullable','integer','min:1','max:6'],'sort_order'=>['nullable','integer','min:0']])+['is_active'=>$request->boolean('is_active',true)]; }
    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
