<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EngagementController extends Controller
{
    public function wishlist(Request $request): View
    {
        $ids = array_values(array_unique(array_map('intval', $request->session()->get('wishlist', []))));
        return view('wishlist.index', ['products'=>Product::query()->whereIn('id',$ids)->where('is_active',true)->get()->sortBy(fn($p)=>array_search($p->id,$ids,true))->values()]);
    }

    public function toggleWishlist(Request $request, Product $product): RedirectResponse
    {
        $ids = array_values(array_unique(array_map('intval', $request->session()->get('wishlist', []))));
        if (in_array($product->id,$ids,true)) { $ids=array_values(array_diff($ids,[$product->id])); $message='از علاقه‌مندی‌ها حذف شد.'; }
        else { $ids[]=$product->id; $message='به علاقه‌مندی‌ها اضافه شد.'; }
        $request->session()->put('wishlist',$ids);
        return back()->with('success',$message);
    }

    public function compare(Request $request): View
    {
        $ids = array_slice(array_values(array_unique(array_map('intval',$request->session()->get('compare',[])))),0,4);
        return view('compare.index',['products'=>Product::query()->with('category')->whereIn('id',$ids)->where('is_active',true)->get()->sortBy(fn($p)=>array_search($p->id,$ids,true))->values()]);
    }

    public function toggleCompare(Request $request, Product $product): RedirectResponse
    {
        $ids = array_values(array_unique(array_map('intval',$request->session()->get('compare',[]))));
        if (in_array($product->id,$ids,true)) { $ids=array_values(array_diff($ids,[$product->id])); $message='از مقایسه حذف شد.'; }
        else { if(count($ids)>=4)return back()->withErrors(['compare'=>'حداکثر ۴ محصول را می‌توانید هم‌زمان مقایسه کنید.']); $ids[]=$product->id; $message='به مقایسه اضافه شد.'; }
        $request->session()->put('compare',$ids);
        return back()->with('success',$message);
    }
}
