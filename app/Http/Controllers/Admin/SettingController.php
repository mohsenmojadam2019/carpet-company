<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $this->guard('settings.view');
        $keys=['store_name','store_tagline','store_phone','store_address','instagram_url','seo_default_title','seo_default_description','shipping_flat','zarinpal_sandbox','kavenegar_sandbox','kavenegar_sender','kavenegar_verify_template'];
        $settings=collect($keys)->mapWithKeys(fn(string $key)=>[$key=>Setting::valueOf($key,'')]);
        $status=['zarinpal_configured'=>filled(Setting::valueOf('zarinpal_merchant_id',config('services.zarinpal.merchant_id'))),'kavenegar_configured'=>filled(Setting::valueOf('kavenegar_api_key',config('services.kavenegar.api_key')))];
        return view('admin.settings.edit',compact('settings','status'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->guard('settings.manage');
        $data=$request->validate([
            'store_name'=>['required','string','max:100'],'store_tagline'=>['nullable','string','max:190'],'store_phone'=>['nullable','string','max:40'],'store_address'=>['nullable','string','max:500'],'instagram_url'=>['nullable','url','max:500'],
            'seo_default_title'=>['nullable','string','max:190'],'seo_default_description'=>['nullable','string','max:320'],'shipping_flat'=>['nullable','integer','min:0'],
            'zarinpal_merchant_id'=>['nullable','string','max:100'],'zarinpal_sandbox'=>['nullable','boolean'],'kavenegar_api_key'=>['nullable','string','max:255'],'kavenegar_sender'=>['nullable','string','max:40'],'kavenegar_verify_template'=>['nullable','string','max:100'],'kavenegar_sandbox'=>['nullable','boolean'],
        ]);
        foreach(['store_name','store_tagline','store_phone','store_address','instagram_url'] as $key)Setting::put($key,$data[$key]??'','general','string',true);
        Setting::put('shipping_flat',(int)($data['shipping_flat']??0),'general','integer',true);
        foreach(['seo_default_title','seo_default_description'] as $key)Setting::put($key,$data[$key]??'','seo','string',true);
        Setting::put('zarinpal_sandbox',$request->boolean('zarinpal_sandbox'),'payments','boolean',false);
        Setting::put('kavenegar_sandbox',$request->boolean('kavenegar_sandbox'),'notifications','boolean',false);
        Setting::put('kavenegar_sender',$data['kavenegar_sender']??'','notifications','string',false);
        Setting::put('kavenegar_verify_template',$data['kavenegar_verify_template']??'','notifications','string',false);
        if(filled($data['zarinpal_merchant_id']??null))Setting::put('zarinpal_merchant_id',$data['zarinpal_merchant_id'],'payments','encrypted',false);
        if(filled($data['kavenegar_api_key']??null))Setting::put('kavenegar_api_key',$data['kavenegar_api_key'],'notifications','encrypted',false);
        return back()->with('success','تنظیمات فروشگاه، پرداخت و پیامک ذخیره شد.');
    }

    private function guard(string $permission):void{abort_unless(request()->user()?->can($permission),403);}
}
