<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($n) => $n->where('order_number', 'like', '%'.$request->string('q').'%')->orWhere('phone', 'like', '%'.$request->string('q').'%')->orWhere('customer_name', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('payment_status'), fn ($q) => $q->where('payment_status', $request->string('payment_status')))
            ->latest()->paginate(25)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['order' => $order->load('items.product')]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,inventory_review,packed,shipped,completed,cancelled,refunded'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $order->update($data);
        return back()->with('success', 'وضعیت سفارش به‌روزرسانی شد.');
    }
}
