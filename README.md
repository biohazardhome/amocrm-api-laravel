# AmoCRM API Laravel 10
AmoCRM api for laravel 10

## Установка
```
$ composer require biohazard/amocrm-api-laravel
$ php artisan vendor:publish --tag=amocrm-api - Create file config/amocrm-api.php
```
.env
```
AMOCRM_ID="e717af4f-171c-475a-a4c7-d2f6f726c3a6"
AMOCRM_SECRET="chlQrrh7YUfgaQ0JuKoJBcBsgt1UdiiQZEAEH0U0dUF8pRPSJ1CXOr7dLLU8fvcr"
AMOCRM_REDIRECT_URI="http://f0783886.xsph.ru/index.php"
AMOCRM_SUBDOMAIN="stalkernikko.amocrm.ru"
```
Example Controller
```
...
use Biohazard\AmoCRMApi\AmoCRMApiClient;
use League\OAuth2\Client\Token\AccessToken;
use AmoCRM\Models\AccountModel;
use AmoCRM\Exceptions\AmoCRMApiException;
...
class Account extends Controller
{
    
    public function index(AmoCRMApiClient $amocrm, AccessToken $accessToken) {
        try {
            $account = $amocrm->account()->getCurrent(AccountModel::getAvailableWith());
            dump($account);
        } catch (AmoCRMApiException $e) {
            echo $e->getMessage();
        }
   }
}
```
Middleware add file `app/Http/Kernel.php`
```
protected $middlewareAliases = [
    ...
    'amocrm-api' => \Biohazard\AmoCRMApi\Middleware\AmoCRMApi::class,
]
```
Route
```
Route::get('/account/', 'App\Http\Controllers\Account@index')
    ->middleware('amocrm-api')
    ->name('account');
```