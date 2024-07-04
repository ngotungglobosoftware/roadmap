<?php

namespace App\Providers;

use App\Models\Tag;
use App\Observers\TagObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute($request->isMethod('get') ? 60 : 10)->response(function (Request $request, array $headers) {
                return response('Too many request', 429, $headers);
            });
        });
        Tag::observe(TagObserver::class);
    }
}
