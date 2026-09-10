<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\User; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request; use Illuminate\Validation\Rule; use Illuminate\View\View; use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    public function index():View { $this->guard('users.view'); return view('admin.users.index',['users'=>User::with('roles')->latest()->paginate(25),'roles'=>Role::orderBy('name')->get()]); }
    public function store(Request $request):RedirectResponse { $this->guard('users.manage'); $data=$request->validate(['name'=>['required','string','max:100'],'email'=>['required','email','max:190',Rule::unique('users','email')],'phone'=>['nullable','string','max:30'],'password'=>['required','string','min:8'],'role'=>['required','exists:roles,name']]); $role=$data['role'];unset($data['role']);$user=User::create($data);$user->syncRoles([$role]);return back()->with('success','کاربر مدیریتی ایجاد شد.'); }
    public function update(Request $request,User $user):RedirectResponse { $this->guard('users.manage'); $data=$request->validate(['name'=>['required','string','max:100'],'phone'=>['nullable','string','max:30'],'role'=>['required','exists:roles,name'],'password'=>['nullable','string','min:8']]);$role=$data['role'];unset($data['role']);if(blank($data['password']??null))unset($data['password']);$user->update($data);$user->syncRoles([$role]);return back()->with('success','کاربر به‌روزرسانی شد.'); }
    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
