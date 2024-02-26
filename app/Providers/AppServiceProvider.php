<?php

namespace App\Providers;

use App\Repositories\DemoTestInquiryRepositoryInterface;
use App\Repositories\DemoTestInquiryRepository;
use App\Repositories\DemoTestRepository;
use App\Repositories\DemoTestRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            DemoTestInquiryRepositoryInterface::class,
            DemoTestInquiryRepository::class
        );

        $this->app->bind(
            DemoTestRepositoryInterface::class,
            DemoTestRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
