<?php

namespace Accordous\SerasaClient\Providers;

use Accordous\SerasaClient\Services\SerasaService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SerasaClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Relative path to the root
     */
    const ROOT_PATH = __DIR__ . '/../..';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            self::ROOT_PATH . '/config/serasa.php' => config_path('serasa.php'),
        ], 'Serasa');
    }

    /**
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            self::ROOT_PATH . '/config/serasa.php', 'serasa'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            SerasaService::class
        ];
    }
}
