<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $this->guard();
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::query()->orderBy('sort_order')->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->guard();
        $data = $this->validated($request);
        $testimonial = Testimonial::create($data + ['verified_at' => now()]);
        if ($request->hasFile('avatar')) {
            $testimonial->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
        return back()->with('success', 'نظر مشتری اضافه شد.');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $this->guard();
        $testimonial->update($this->validated($request));
        if ($request->hasFile('avatar')) {
            $testimonial->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
        return back()->with('success', 'نظر مشتری به‌روزرسانی شد.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->guard();
        $testimonial->delete();
        return back()->with('success', 'نظر مشتری حذف شد.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'customer_name' => ['required','string','max:120'],
            'city' => ['nullable','string','max:100'],
            'title' => ['nullable','string','max:190'],
            'body' => ['required','string','max:1600'],
            'rating' => ['required','integer','between:1,5'],
            'sort_order' => ['nullable','integer','min:0','max:9999'],
            'avatar' => ['nullable','image','max:3072'],
        ]);
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        unset($data['avatar']);
        return $data;
    }

    private function guard(): void
    {
        abort_unless(request()->user()?->can('settings.manage'), 403);
    }
}
