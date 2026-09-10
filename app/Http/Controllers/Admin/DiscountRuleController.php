<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Category; use App\Models\DiscountRule; use App\Models\Product; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request; use Illuminate\Validation\Rule; use Illuminate\View\View;
class DiscountRuleController extends Controller
{
    public function index():View { $this->guard('discounts.view'); return view('admin.discount-rules.index',['rules'=>DiscountRule::orderByDesc('priority')->latest()->paginate(20)]); }
    public function create():View { $this->guard('discounts.manage'); return view('admin.discount-rules.form',['rule'=>new DiscountRule(),'products'=>Product::orderBy('name')->get(),'categories'=>Category::orderBy('name')->get()]); }
    public function store(Request $request):RedirectResponse { $this->guard('discounts.manage'); DiscountRule::create($this->payload($request)); return redirect()->route('admin.discount-rules.index')->with('success','قانون تخفیف ایجاد شد.'); }
    public function edit(DiscountRule $discount_rule):View { $this->guard('discounts.manage'); return view('admin.discount-rules.form',['rule'=>$discount_rule,'products'=>Product::orderBy('name')->get(),'categories'=>Category::orderBy('name')->get()]); }
    public function update(Request $request,DiscountRule $discount_rule):RedirectResponse { $this->guard('discounts.manage'); $discount_rule->update($this->payload($request)); return back()->with('success','قانون تخفیف به‌روزرسانی شد.'); }
    public function destroy(DiscountRule $discount_rule):RedirectResponse { $this->guard('discounts.manage'); $discount_rule->delete(); return back()->with('success','قانون تخفیف حذف شد.'); }
    private function payload(Request $request):array { $data=$request->validate(['name'=>['required','string','max:190'],'type'=>['required',Rule::in(['percent','fixed'])],'scope'=>['required',Rule::in(['all','product','category'])],'scope_value'=>['nullable','array'],'scope_value.*'=>['integer'],'value'=>['required','integer','min:1'],'priority'=>['nullable','integer'],'starts_at'=>['nullable','date'],'ends_at'=>['nullable','date','after_or_equal:starts_at']]); $scopeValues=$data['scope']==='all'?[]:array_values(array_map('intval',$data['scope_value']??[])); return $data+['scope_value'=>$scopeValues,'priority'=>(int)($data['priority']??0),'is_active'=>$request->boolean('is_active',true)]; }
    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
