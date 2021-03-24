<?php

namespace Bdelespierre\HasUuid\Providers;

use Bdelespierre\HasUuid\Contracts\UuidGenerator;
use Bdelespierre\HasUuid\Contracts\UuidValidator;
use Bdelespierre\HasUuid\UuidService;
use Illuminate\Support\ServiceProvider;

class UuidServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     */
    public function register()
    {
        $this->app->singleton('uuid', function ($app) {
            return new UuidService($app['config']);
        });

        $this->app->alias('uuid', 'uuid.generator');
        $this->app->alias('uuid', 'uuid.validator');
    }
}
