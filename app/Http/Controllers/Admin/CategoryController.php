<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()->withCount('products')->with('parent')->orderBy('sort_order')->get(),
            'parents' => Category::query()->whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'], 'slug' => ['nullable', 'string', 'max:120', 'unique:categories,slug'],
            'parent_id' => ['nullable', 'exists:categories,id'], 'description' => ['nullable', 'string', 'max:1000'], 'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']) ?: 'category-'.Str::lower(Str::random(7));
        $data['is_active'] = $request->boolean('is_active', true);
        Category::create($data);
        return back()->with('success', 'دسته‌بندی ایجاد شد.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'], 'slug' => ['required', 'string', 'max:120', 'unique:categories,slug,'.$category->id],
            'parent_id' => ['nullable', 'exists:categories,id', 'not_in:'.$category->id], 'description' => ['nullable', 'string', 'max:1000'], 'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);
        return back()->with('success', 'دسته‌بندی به‌روزرسانی شد.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists() || $category->children()->exists()) {
            return back()->withErrors(['category' => 'دسته‌ای که محصول یا زیرمجموعه دارد قابل حذف نیست.']);
        }
        $category->delete();
        return back()->with('success', 'دسته‌بندی حذف شد.');
    }
}
