<?php

namespace App\Providers;

use App\Facades\Helper\Helper;
use App\Services\Shared\Cache\CacheService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind('helper', function () {
            return new Helper();
        });

        $this->app->bind('cacheService', function () {
            return new CacheService();
        });

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(3)
                ->by(optional($request->user())->id ?: $request->ip());
        });
        date_default_timezone_set('Asia/Dushanbe');
    }
}
