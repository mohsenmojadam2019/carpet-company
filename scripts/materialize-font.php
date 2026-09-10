<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$encoded = '';

for ($part = 1; $part <= 4; $part++) {
    $file = $root."/resources/fonts/byekan.woff2.part{$part}.b64";
    if (!is_file($file)) {
        fwrite(STDERR, "Missing BYekan font part: {$file}\n");
        exit(1);
    }
    $encoded .= trim((string) file_get_contents($file));
}

$bytes = base64_decode($encoded, true);
if ($bytes === false || !str_starts_with($bytes, 'wOF2')) {
    fwrite(STDERR, "Invalid BYekan WOFF2 payload.\n");
    exit(1);
}

$directory = $root.'/public/fonts';
if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
    fwrite(STDERR, "Could not create {$directory}\n");
    exit(1);
}

$target = $directory.'/BYekan.woff2';
if (file_put_contents($target, $bytes) === false) {
    fwrite(STDERR, "Could not write {$target}\n");
    exit(1);
}

fwrite(STDOUT, "Materialized public/fonts/BYekan.woff2 (".strlen($bytes)." bytes).\n");
