<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Order; use App\Models\Product; use Illuminate\Support\Facades\DB; use Illuminate\View\View;
class ReportController extends Controller
{
    public function index():View { abort_unless(request()->user()?->can('reports.view'),403); $salesByDay=Order::where('payment_status','paid')->where('paid_at','>=',now()->subDays(30))->selectRaw('DATE(paid_at) as day, SUM(total) as revenue, COUNT(*) as orders')->groupBy('day')->orderBy('day')->get(); $topProducts=DB::table('order_items')->select('product_name',DB::raw('SUM(quantity) as qty'),DB::raw('SUM(total_price) as revenue'))->groupBy('product_name')->orderByDesc('revenue')->limit(10)->get(); $lowStock=Product::where('is_active',true)->where('stock','<=',3)->orderBy('stock')->limit(20)->get(); return view('admin.reports.index',compact('salesByDay','topProducts','lowStock')); }
}
