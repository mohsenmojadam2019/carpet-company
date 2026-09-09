<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()->published()->with('category')
            ->when($request->filled('q'), fn ($query) => $query->where(function ($nested) use ($request) {
                $term = trim((string) $request->string('q'));
                $nested->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('material', 'like', "%{$term}%")
                    ->orWhere('origin', 'like', "%{$term}%");
            }))
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $request->string('category'))))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', (int) $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', (int) $request->input('max_price')));

        match ($request->string('sort')->toString()) {
            'price_asc' => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            'oldest' => $products->oldest(),
            default => $products->latest(),
        };

        return view('catalog.index', [
            'products' => $products->paginate(18)->withQueryString(),
            'categories' => Category::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Product $product, PricingService $pricing): View
    {
        abort_unless($product->is_active, 404);
        $product->load('category');

        return view('products.show', [
            'product' => $product,
            'price' => $pricing->productPrice($product),
            'related' => Product::query()->published()
                ->where('id', '!=', $product->id)
                ->where('category_id', $product->category_id)
                ->limit(4)->get(),
        ]);
    }
}
