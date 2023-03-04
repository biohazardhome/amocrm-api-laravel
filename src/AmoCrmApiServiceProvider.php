<?php

namespace Biohazard\AmoCRMApi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;

class AmoCrmApiServiceProvider extends ServiceProvider
{

    protected $defer = false;

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $config = realpath(__DIR__ . '/../config/amocrm-api.php');

        $this->publishes([
            $config => config_path('amocrm-api.php'),
        ], 'amocrm-api');

        $this->mergeConfigFrom($config, 'amocrm-api');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AmoCRMApiClient::class, function (Container $app) {
            $config = $app['config']['amocrm-api'];

            $apiClient = new AmoCRMApiClient($config['id'], $config['secret'], $config['redirect_uri']);
            $apiClient->setAccountBaseDomain($config['subdomain']);
            $accessToken = $apiClient->getTokenFile();
            $apiClient->setAccessToken($accessToken);
            return $apiClient;
        });
    }

    public function provides()
    {
        return [
            AmoCRMApiClient::class,
        ];
    }
}