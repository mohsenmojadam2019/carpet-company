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
        $paidRevenue = (int) Order::query()->where('payment_status', 'paid')->sum('total');

        return view('admin.dashboard', [
            'metrics' => [
                'revenue' => $paidRevenue,
                'orders' => Order::query()->count(),
                'products' => Product::query()->count(),
                'lowStock' => Product::query()->where('stock', '<=', 3)->count(),
            ],
            'recentOrders' => Order::query()->latest()->limit(8)->get(),
            'topProducts' => DB::table('order_items')
                ->select('product_name', DB::raw('SUM(quantity) as sold'), DB::raw('SUM(total_price) as revenue'))
                ->groupBy('product_name')->orderByDesc('sold')->limit(5)->get(),
        ]);
    }
}
