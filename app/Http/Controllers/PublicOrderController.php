<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class PublicOrderController extends Controller
{
    public function success(Order $order): View
    {
        $order->load('items');
        return view('orders.success', compact('order'));
    }

    public function invoice(Order $order): View
    {
        abort_unless($order->payment_status === 'paid' && filled($order->invoice_number), 404);
        $order->load('items');
        return view('orders.invoice', compact('order'));
    }
}
