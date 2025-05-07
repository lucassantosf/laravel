<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;  
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\AppointmentRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use App\Services\Contracts\AppointmentServiceInterface;
use App\Services\Contracts\PostServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\AppointmentService;
use App\Services\PostService;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        $this->app->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);
        $this->app->bind(AppointmentServiceInterface::class, AppointmentService::class);
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
