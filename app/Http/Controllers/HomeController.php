<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'categories' => Category::query()->whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->limit(6)->get(),
            'featuredProducts' => Product::query()->published()->featured()->with('category')->limit(8)->get(),
            'projects' => Project::query()->where('is_active', true)->where('is_featured', true)->latest()->limit(4)->get(),
        ]);
    }
}
