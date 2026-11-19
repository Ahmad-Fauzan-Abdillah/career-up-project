<?php

namespace App\Providers;

use App\Contracts\PaymentGateway;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\Subscription;
use App\Observers\ApplicationObserver;
use App\Observers\JobListingObserver;
use App\Observers\SubscriptionObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Payments\ManualPaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGateway::class, ManualPaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());

        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(10)
            ->by(strtolower((string) $request->input('email')).'|'.$request->ip()));
        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('password-reset', fn (Request $request) => Limit::perMinute(3)
            ->by(strtolower((string) $request->input('email')).'|'.$request->ip()));
        RateLimiter::for('verification-resend', fn (Request $request) => Limit::perMinute(3)
            ->by((string) $request->user()?->getAuthIdentifier()));
        RateLimiter::for('uploads', fn (Request $request) => Limit::perMinute(10)
            ->by((string) ($request->user()?->getAuthIdentifier() ?? $request->ip())));

        Application::observe(ApplicationObserver::class);
        JobListing::observe(JobListingObserver::class);
        Subscription::observe(SubscriptionObserver::class);
    }
}
