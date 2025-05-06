<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;  
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\PostRepository;
use App\Services\Contracts\PostServiceInterface;
use App\Services\PostService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::loadKeysFrom(storage_path('oauth'));

        Passport::tokensExpireIn(now()->addMinutes(5));
        Passport::refreshTokensExpireIn(now()->addMinutes(5));
        Passport::personalAccessTokensExpireIn(now()->addMinutes(5));
    }
}
