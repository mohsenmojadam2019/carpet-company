{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($urls as $item)
    <url>
        <loc>{{ $item['loc'] }}</loc>
        @if(!empty($item['lastmod']))<lastmod>{{ $item['lastmod']->toAtomString() }}</lastmod>@endif
        <changefreq>{{ str_contains($item['loc'], '/product/') ? 'weekly' : 'daily' }}</changefreq>
        <priority>{{ $item['loc'] === route('home') ? '1.0' : (str_contains($item['loc'], '/product/') ? '0.8' : '0.7') }}</priority>
    </url>
@endforeach
</urlset>
