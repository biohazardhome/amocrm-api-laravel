<?php

namespace Biohazard\AmoCRMApi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use League\OAuth2\Client\Token\AccessToken;

class AmoCrmApiServiceProvider extends ServiceProvider
{

    protected $defer = false;
    private const CONFIG_PATH = __DIR__.'/../config/amocrm-api.php';

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $config = self::CONFIG_PATH;

        $this->publishes([
            $config => config_path('amocrm-api.php'),
        ], 'amocrm-api');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'amocrm-api');
        $config = config('amocrm-api');

        $apiClient = new AmoCRMApiClient($config['id'], $config['secret'], $config['redirect_uri']);
        $apiClient->setAccountBaseDomain($config['subdomain']);
        $accessToken = $apiClient->getTokenFile();

        $this->app->singleton(AmoCRMApiClient::class, function (Container $app) use($apiClient, $accessToken) {
            $apiClient->setAccessToken($accessToken);
            return $apiClient;
        });

        $this->app->singleton(AccessToken::class, function (Container $app) use($accessToken) {
            return $accessToken;
        });
    }

    public function provides()
    {
        return [
            AmoCRMApiClient::class,
        ];
    }
}