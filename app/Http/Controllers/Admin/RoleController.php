<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request; use Illuminate\Validation\Rule; use Illuminate\View\View; use Spatie\Permission\Models\Permission; use Spatie\Permission\Models\Role;
class RoleController extends Controller
{
    public function index():View { $this->guard('users.view'); return view('admin.roles.index',['roles'=>Role::with('permissions')->withCount('users')->orderBy('name')->get(),'permissions'=>Permission::orderBy('name')->get()]); }
    public function store(Request $request):RedirectResponse { $this->guard('users.manage'); $data=$request->validate(['name'=>['required','string','max:80',Rule::unique('roles','name')],'permissions'=>['nullable','array'],'permissions.*'=>['exists:permissions,name']]); $role=Role::create(['name'=>$data['name'],'guard_name'=>'web']); $role->syncPermissions($data['permissions']??[]); return back()->with('success','نقش ایجاد شد.'); }
    public function update(Request $request,Role $role):RedirectResponse { $this->guard('users.manage'); abort_if($role->name==='super-admin',403,'نقش super-admin قابل ویرایش نیست.'); $data=$request->validate(['permissions'=>['nullable','array'],'permissions.*'=>['exists:permissions,name']]); $role->syncPermissions($data['permissions']??[]); return back()->with('success','دسترسی‌های نقش ذخیره شد.'); }
    public function destroy(Role $role):RedirectResponse { $this->guard('users.manage'); abort_if($role->name==='super-admin',403,'نقش super-admin قابل حذف نیست.'); $role->delete(); return back()->with('success','نقش حذف شد.'); }
    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
