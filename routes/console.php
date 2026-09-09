<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('store:health', function () {
    $this->info('Carpet Company application is healthy.');
})->purpose('Run a lightweight application health check');
