<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            ['loc'=>route('home'),'lastmod'=>now()],
            ['loc'=>route('catalog.index'),'lastmod'=>now()],
            ['loc'=>route('projects.index'),'lastmod'=>now()],
            ['loc'=>route('pages.about'),'lastmod'=>now()],
            ['loc'=>route('pages.contact'),'lastmod'=>now()],
            ['loc'=>route('pages.custom-order'),'lastmod'=>now()],
            ['loc'=>route('pages.services'),'lastmod'=>now()],
            ['loc'=>route('pages.faq'),'lastmod'=>now()],
        ])->merge(Product::published()->get(['slug','updated_at'])->map(fn($p)=>['loc'=>route('products.show',$p),'lastmod'=>$p->updated_at]))
          ->merge(Project::where('is_active',true)->get(['slug','updated_at'])->map(fn($p)=>['loc'=>route('projects.show',$p),'lastmod'=>$p->updated_at]));

        return response(view('sitemap',compact('urls'))->render(),200,['Content-Type'=>'application/xml']);
    }
}
