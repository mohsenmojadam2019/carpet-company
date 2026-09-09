<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()->with('category')
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($n) => $n->where('name', 'like', '%'.$request->string('q').'%')->orWhere('sku', 'like', '%'.$request->string('q').'%')))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product(), 'categories' => Category::query()->where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Product::create($this->payload($request));
        return redirect()->route('admin.products.index')->with('success', 'محصول ایجاد شد.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::query()->orderBy('name')->get()]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->payload($request, $product));
        return redirect()->route('admin.products.index')->with('success', 'محصول به‌روزرسانی شد.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->update(['is_active' => false]);
        return back()->with('success', 'محصول غیرفعال شد.');
    }

    private function payload(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'], 'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190'], 'sku' => ['required', 'string', 'max:80', 'unique:products,sku'.($product ? ','.$product->id : '')],
            'short_description' => ['nullable', 'string', 'max:500'], 'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'], 'sale_price' => ['nullable', 'integer', 'min:0'], 'stock' => ['required', 'integer', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'], 'height' => ['nullable', 'numeric', 'min:0'], 'material' => ['nullable', 'string', 'max:100'],
            'weave' => ['nullable', 'string', 'max:100'], 'density' => ['nullable', 'string', 'max:100'], 'origin' => ['nullable', 'string', 'max:100'],
            'images_text' => ['nullable', 'string'], 'meta_title' => ['nullable', 'string', 'max:190'], 'meta_description' => ['nullable', 'string', 'max:320'],
        ]);

        $slug = $data['slug'] ?: Str::slug($data['name']);
        if ($slug === '') $slug = 'product-'.Str::lower(Str::random(8));
        $base = $slug; $counter = 2;
        while (Product::query()->where('slug', $slug)->when($product, fn ($q) => $q->whereKeyNot($product->id))->exists()) $slug = $base.'-'.$counter++;

        $images = collect(preg_split('/\r\n|\r|\n/', (string) ($data['images_text'] ?? '')))->map('trim')->filter()->values()->all();
        unset($data['images_text']);

        return $data + [
            'slug' => $slug, 'images' => $images ?: ($product?->images ?: ['/images/rug-01.svg']),
            'colors' => $product?->colors ?: [], 'variants' => $product?->variants ?: [], 'specifications' => $product?->specifications ?: [],
            'featured' => $request->boolean('featured'), 'is_active' => $request->boolean('is_active', true),
        ];
    }
}
