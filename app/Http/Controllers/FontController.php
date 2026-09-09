<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class FontController extends Controller
{
    public function byekan(): Response
    {
        $encoded = '';

        for ($part = 1; $part <= 4; $part++) {
            $path = resource_path("fonts/byekan.woff2.part{$part}.b64");
            abort_unless(is_file($path), 404);
            $encoded .= trim((string) file_get_contents($path));
        }

        $bytes = base64_decode($encoded, true);
        abort_if($bytes === false || !str_starts_with($bytes, 'wOF2'), 500, 'Invalid bundled font data.');

        return response($bytes, 200, [
            'Content-Type' => 'font/woff2',
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'Content-Length' => (string) strlen($bytes),
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
