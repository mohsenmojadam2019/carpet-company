<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $paidRevenue=(int)Order::where('payment_status','paid')->sum('total');
        $monthRevenue=(int)Order::where('payment_status','paid')->where('paid_at','>=',now()->startOfMonth())->sum('total');
        $rawDaily=Order::where('payment_status','paid')->where('paid_at','>=',now()->subDays(13)->startOfDay())->selectRaw('DATE(paid_at) as day, SUM(total) as revenue')->groupBy('day')->pluck('revenue','day');
        $salesSeries=collect(range(13,0))->map(function(int $daysAgo)use($rawDaily){$date=now()->subDays($daysAgo);return ['day'=>$date->format('Y-m-d'),'label'=>$date->format('m/d'),'revenue'=>(int)($rawDaily[$date->format('Y-m-d')]??0)];});
        $maxRevenue=max(1,(int)$salesSeries->max('revenue'));
        return view('admin.dashboard',[
            'metrics'=>['revenue'=>$paidRevenue,'monthRevenue'=>$monthRevenue,'orders'=>Order::count(),'paidOrders'=>Order::where('payment_status','paid')->count(),'products'=>Product::count(),'lowStock'=>Product::where('stock','<=',3)->count(),'customers'=>Order::distinct('phone')->count('phone')],
            'recentOrders'=>Order::latest()->limit(8)->get(),
            'topProducts'=>DB::table('order_items')->select('product_name',DB::raw('SUM(quantity) as sold'),DB::raw('SUM(total_price) as revenue'))->groupBy('product_name')->orderByDesc('sold')->limit(5)->get(),
            'lowStockProducts'=>Product::where('is_active',true)->where('stock','<=',3)->orderBy('stock')->limit(5)->get(),
            'pipeline'=>Order::select('status',DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total','status'),
            'salesSeries'=>$salesSeries,'maxRevenue'=>$maxRevenue,
        ]);
    }
}
