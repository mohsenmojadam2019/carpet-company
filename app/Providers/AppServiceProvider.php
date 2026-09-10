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
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Gate::before(fn (User $user, string $ability): ?bool => $user->hasRole('super-admin') ? true : null);

        View::composer('layouts.app', function ($view): void {
            $settings = [
                'store_name' => 'خانه فرش',
                'store_tagline' => 'فرش برای معماری ماندگار',
                'store_phone' => '',
                'store_address' => '',
                'instagram_url' => '',
                'seo_default_title' => 'خانه فرش | خرید قالی، فرش و موکت',
                'seo_default_description' => 'فروشگاه تخصصی قالی، فرش، تابلو فرش، موکت و فرشینه با انتخاب حرفه‌ای و پرداخت امن.',
            ];
            $menuItems = collect();

            try {
                if (Schema::hasTable('settings')) {
                    foreach (array_keys($settings) as $key) {
                        $settings[$key] = Setting::valueOf($key, $settings[$key]);
                    }
                }
                if (Schema::hasTable('menu_items')) {
                    $menuItems = MenuItem::query()->with('children')->whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get();
                }
            } catch (Throwable) {
                // Keep the storefront renderable during a fresh install before migrations finish.
            }

            $view->with(compact('settings', 'menuItems'));
        });

        if ($this->app->environment('production')) URL::forceScheme('https');
    }
}
