<?php

namespace App\Providers;

use App\Features\Payments\PaymentManager;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: PaymentManager::class,
            concrete: fn (Application $app): PaymentManager => new PaymentManager($app),
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        JsonResource::withoutWrapping();

        RateLimiter::for('payment-gateway', function (Request $request): Limit {

            return Limit::perMinute(180)->by($request->user()?->id ?: $request->ip());

        });

        Model::unguard();

        Model::shouldBeStrict();
    }
}
