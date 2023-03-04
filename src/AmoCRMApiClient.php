<?php

namespace Biohazard\AmoCRMApi;

use AmoCRM\Client\AmoCRMApiClient as BaseAmoCRMApiClient;
use League\OAuth2\Client\Token\AccessToken;
use Illuminate\Support\Facades\Storage;

class AmoCRMApiClient extends BaseAmoCRMApiClient
{

	public const TOKEN_FILE = 'token_info.json';

	/**
	 * @param array $accessToken
	 */
	public function saveTokenFile($accessToken)
	{
	    if (
	        isset($accessToken)
	        && isset($accessToken['accessToken'])
	        && isset($accessToken['refreshToken'])
	        && isset($accessToken['expires'])
	        && isset($accessToken['baseDomain'])
	    ) {
	        $data = [
	            'accessToken' => $accessToken['accessToken'],
	            'expires' => $accessToken['expires'],
	            'refreshToken' => $accessToken['refreshToken'],
	            'baseDomain' => $accessToken['baseDomain'],
	        ];

	        $storage = Storage::disk('local');
	        $storage->put(self::TOKEN_FILE, json_encode($data));
	    } else {
	        exit('Invalid access token ' . var_export($accessToken, true));
	    }
	}

	/**
	 * @return AccessToken
	 */
	public function getTokenFile()
	{
	    $storage = Storage::disk('local');

	    if ($storage->missing(self::TOKEN_FILE)) {
	        exit('Access token file not found');
	    }

	    $accessToken = json_decode($storage->get(self::TOKEN_FILE), true);

	    if (
	        isset($accessToken)
	        && isset($accessToken['accessToken'])
	        && isset($accessToken['refreshToken'])
	        && isset($accessToken['expires'])
	        && isset($accessToken['baseDomain'])
	    ) {
	        return new AccessToken([
	            'access_token' => $accessToken['accessToken'],
	            'refresh_token' => $accessToken['refreshToken'],
	            'expires' => $accessToken['expires'],
	            'baseDomain' => $accessToken['baseDomain'],
	        ]);
	    } else {
	        exit('Invalid access token ' . var_export($accessToken, true));
	    }
	}

}