<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('customers.view'),403);
        $customers = Order::query()
            ->when($request->filled('q'), fn($q) => $q->where(fn($n) => $n->where('customer_name','like','%'.$request->string('q').'%')->orWhere('phone','like','%'.$request->string('q').'%')->orWhere('email','like','%'.$request->string('q').'%')))
            ->selectRaw("phone, MAX(customer_name) as customer_name, MAX(email) as email, COUNT(*) as order_count, SUM(CASE WHEN payment_status = 'paid' THEN total ELSE 0 END) as total_spent, MAX(created_at) as last_order_at")
            ->groupBy('phone')->orderByDesc('last_order_at')->paginate(30)->withQueryString();
        return view('admin.customers.index',compact('customers'));
    }
}
