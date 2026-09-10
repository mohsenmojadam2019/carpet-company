<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Coupon; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request; use Illuminate\Validation\Rule; use Illuminate\View\View;
class CouponController extends Controller
{
    public function index():View { $this->guard('discounts.view'); return view('admin.coupons.index',['coupons'=>Coupon::latest()->paginate(20)]); }
    public function create():View { $this->guard('discounts.manage'); return view('admin.coupons.form',['coupon'=>new Coupon()]); }
    public function store(Request $request):RedirectResponse { $this->guard('discounts.manage'); Coupon::create($this->payload($request)); return redirect()->route('admin.coupons.index')->with('success','کد تخفیف ایجاد شد.'); }
    public function edit(Coupon $coupon):View { $this->guard('discounts.manage'); return view('admin.coupons.form',compact('coupon')); }
    public function update(Request $request,Coupon $coupon):RedirectResponse { $this->guard('discounts.manage'); $coupon->update($this->payload($request,$coupon)); return back()->with('success','کد تخفیف به‌روزرسانی شد.'); }
    public function destroy(Coupon $coupon):RedirectResponse { $this->guard('discounts.manage'); $coupon->delete(); return back()->with('success','کد تخفیف حذف شد.'); }
    private function payload(Request $request,?Coupon $coupon=null):array { $data=$request->validate(['code'=>['required','string','max:80',Rule::unique('coupons','code')->ignore($coupon)],'type'=>['required',Rule::in(['percent','fixed'])],'value'=>['required','integer','min:1'],'min_order'=>['nullable','integer','min:0'],'max_discount'=>['nullable','integer','min:0'],'usage_limit'=>['nullable','integer','min:1'],'starts_at'=>['nullable','date'],'ends_at'=>['nullable','date','after_or_equal:starts_at']]); return $data+['min_order'=>(int)($data['min_order']??0),'is_active'=>$request->boolean('is_active',true)]; }
    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
