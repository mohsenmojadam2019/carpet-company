<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $this->guard('orders.view');
        $orders=Order::query()
            ->when($request->filled('q'),fn($q)=>$q->where(fn($n)=>$n->where('order_number','like','%'.$request->string('q').'%')->orWhere('invoice_number','like','%'.$request->string('q').'%')->orWhere('phone','like','%'.$request->string('q').'%')->orWhere('customer_name','like','%'.$request->string('q').'%')))
            ->when($request->filled('status'),fn($q)=>$q->where('status',$request->string('status')))
            ->when($request->filled('payment_status'),fn($q)=>$q->where('payment_status',$request->string('payment_status')))
            ->latest()->paginate(25)->withQueryString();
        return view('admin.orders.index',compact('orders'));
    }

    public function show(Order $order): View
    {
        $this->guard('orders.view');
        return view('admin.orders.show',['order'=>$order->load(['items.product','statusHistories.actor'])]);
    }

    public function update(Request $request,Order $order): RedirectResponse
    {
        $this->guard('orders.manage');
        $data=$request->validate(['status'=>['required','in:pending,processing,inventory_review,packed,shipped,completed,cancelled,refunded'],'notes'=>['nullable','string','max:2000'],'status_note'=>['nullable','string','max:1000']]);
        DB::transaction(function () use ($order,$data,$request): void {
            $order->update(['notes'=>$data['notes']??null]);
            $order->transitionTo($data['status'],$data['status_note']??'تغییر وضعیت توسط مدیریت.',(int)$request->user()->id);
        });
        return back()->with('success','وضعیت سفارش و تاریخچه آن به‌روزرسانی شد.');
    }

    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
