<?php

namespace Biohazard\AmoCRMApi\Facade;

use Illuminate\Support\Facades\Facade;
use Biohazard\AmoCRMApi\AmoCRMApiClient;

class AmoCRMApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AmoCRMApiClient::class;
    }
}