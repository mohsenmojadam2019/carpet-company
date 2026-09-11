<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function contact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'phone' => ['required','string','max:30'],
            'email' => ['nullable','email','max:190'],
            'subject' => ['nullable','string','max:190'],
            'message' => ['required','string','max:3000'],
        ]);

        Inquiry::create($data + ['type' => 'contact', 'status' => 'new']);

        return back()->with('success', 'پیام شما ثبت شد. کارشناسان مجموعه با شما تماس می‌گیرند.');
    }

    public function customOrder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'phone' => ['required','string','max:30'],
            'email' => ['nullable','email','max:190'],
            'category' => ['required','string','max:120'],
            'width' => ['nullable','numeric','min:0','max:10000'],
            'height' => ['nullable','numeric','min:0','max:10000'],
            'budget' => ['nullable','integer','min:0'],
            'message' => ['nullable','string','max:3000'],
        ]);

        Inquiry::create([
            'type' => 'custom_order',
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'subject' => 'سفارش اختصاصی '.$data['category'],
            'message' => $data['message'] ?? null,
            'meta' => [
                'category' => $data['category'],
                'width' => $data['width'] ?? null,
                'height' => $data['height'] ?? null,
                'budget' => $data['budget'] ?? null,
            ],
            'status' => 'new',
        ]);

        return back()->with('success', 'درخواست سفارش اختصاصی ثبت شد. برای بررسی جزئیات با شما تماس می‌گیریم.');
    }
}
