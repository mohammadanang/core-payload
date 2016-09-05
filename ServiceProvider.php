<?php

namespace Apollo16\Core\Payload;

use Apollo16\Core\Contracts\Payload\Broker as BrokerContract;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Payload service provider.
 *
 * @author      mohammad.anang  <m.anangnur@gmail.com>
 */

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind('apollo16.payload', function($app) {
            return new Broker($app['encrypter'], $app['request']);
        }, true);

        $this->app->alias('apollo16.payload', Broker::class);
        $this->app->alias('apollo16.payload', BrokerContract::class);
    }
}