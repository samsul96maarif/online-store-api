<?php

namespace App\Providers;

use App\Repositories\CartRepository;
use App\Repositories\Contracts\CartContract;
use App\Repositories\Contracts\InvoiceContract;
use App\Repositories\Contracts\ProductContract;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProductContract::class, ProductRepository::class);
        $this->app->singleton(CartContract::class, CartRepository::class);
        $this->app->singleton(InvoiceContract::class, InvoiceRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
