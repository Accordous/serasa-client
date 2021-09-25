<?php

namespace Accordous\SerasaClient\Tests;

use Accordous\SerasaClient\Providers\SerasaClientServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * add the package provider
     *
     * @param  Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            SerasaClientServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);

        $app['config']->set('serasa.host', 'https://mqlinuxext.serasa.com.br');
        $app['config']->set('serasa.api', '/Homologa/consultahttps');
        $app['config']->set('serasa.user', env('SERASA_USER'));
        $app['config']->set('serasa.password', env('SERASA_PASSWORD'));
    }
}
