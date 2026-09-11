<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('customers.view'), 403);

        $inquiries = Inquiry::query()
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        abort_unless($request->user()?->can('orders.manage'), 403);
        $data = $request->validate(['status' => ['required','in:new,contacted,quoted,done,cancelled']]);
        $inquiry->update([
            'status' => $data['status'],
            'handled_at' => in_array($data['status'], ['done','cancelled'], true) ? now() : $inquiry->handled_at,
        ]);
        return back()->with('success', 'وضعیت درخواست به‌روزرسانی شد.');
    }
}
