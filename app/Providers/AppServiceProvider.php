<?php

namespace App\Providers;

use App\Models\MenuItem;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Gate::before(fn (User $user, string $ability): ?bool => $user->hasRole('super-admin') ? true : null);

        View::composer(['layouts.app'], function ($view): void {
            $settings = [];
            $menuItems = collect();
            if (Schema::hasTable('settings')) {
                foreach (['store_name','store_tagline','store_phone','store_address','instagram_url'] as $key) $settings[$key] = Setting::valueOf($key);
            }
            if (Schema::hasTable('menu_items')) {
                $menuItems = MenuItem::query()->with('children')->whereNull('parent_id')->where('is_active', true)->orderBy('column')->orderBy('sort_order')->get();
            }
            $view->with(compact('settings','menuItems'));
        });

        if ($this->app->environment('production')) URL::forceScheme('https');
    }
}
