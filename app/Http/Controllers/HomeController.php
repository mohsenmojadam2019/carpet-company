<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['products' => fn ($query) => $query
                ->published()
                ->orderByDesc('featured')
                ->latest()])
            ->get();

        return view('home', [
            'categories' => $categories,
            'featuredProducts' => Product::query()
                ->published()
                ->featured()
                ->with('category')
                ->limit(8)
                ->get(),
            'projects' => Project::query()
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->limit(4)
                ->get(),
            'testimonials' => Testimonial::query()
                ->where('is_active', true)
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->latest()
                ->limit(12)
                ->get(),
        ]);
    }
}
