<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Setting; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request; use Illuminate\View\View;
class SettingController extends Controller
{
    public function edit():View { $this->guard('settings.view'); $keys=['store_name','store_tagline','store_phone','store_address','instagram_url','seo_default_title','seo_default_description','shipping_flat']; $settings=Setting::whereIn('key',$keys)->pluck('value','key'); return view('admin.settings.edit',compact('settings')); }
    public function update(Request $request):RedirectResponse { $this->guard('settings.manage'); $data=$request->validate(['store_name'=>['required','string','max:100'],'store_tagline'=>['nullable','string','max:190'],'store_phone'=>['nullable','string','max:40'],'store_address'=>['nullable','string','max:500'],'instagram_url'=>['nullable','url','max:500'],'seo_default_title'=>['nullable','string','max:190'],'seo_default_description'=>['nullable','string','max:320'],'shipping_flat'=>['nullable','integer','min:0']]); foreach($data as $key=>$value)Setting::put($key,$value??'',in_array($key,['seo_default_title','seo_default_description'])?'seo':'general',$key==='shipping_flat'?'integer':'string'); return back()->with('success','تنظیمات ذخیره شد.'); }
    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
