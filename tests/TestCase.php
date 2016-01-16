<?php

namespace Seliton\Client\Tests;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
	protected static $accessToken = null;

	protected static function getAccessToken()
	{
		if (is_null(static::$accessToken)) {
			$scopes = array(
				'read_attributes',
				'write_attributes',
				'read_brands',
				'write_brands',
				'read_categories',
				'write_categories',
				'read_customers',
				'write_customers',
				'read_orders',
				'read_pages',
				'write_pages',
				'read_products',
				'write_products',
			);

			$loader = new \josegonzalez\Dotenv\Loader(array (
				__DIR__.'/.env',
				__DIR__.'/.env.default'
			));
			$env = $loader->parse()->toArray();

			$authorizeUrl = $env['SELITON_PARTNERS_URL'].'/authorize?client_id=testclient&response_type=code'.
				'&state=xyz&shop=dev-1.myseliton.com&scope='.implode('%20', $scopes);
			$curl = curl_init($authorizeUrl);
			curl_setopt($curl, CURLOPT_POSTFIELDS, array ('authorized' => 'Accept'));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_exec($curl);
			$info = curl_getinfo($curl);
			$code = substr(
				$info['redirect_url'],
				strlen($env['SELITON_PARTNERS_URL'].'/recent-orders-app?code='),
				40
			);

			$curl = curl_init($env['SELITON_PARTNERS_URL'].'/token');
			curl_setopt($curl, CURLOPT_POSTFIELDS, array (
				'grant_type' => 'authorization_code',
				'code' => $code
			));
			curl_setopt($curl, CURLOPT_USERPWD, 'testclient:testpass');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$responseBody = curl_exec($curl);
			$responseJsonDecoded = json_decode($responseBody);
			static::$accessToken = $responseJsonDecoded->access_token;
		}

		return static::$accessToken;
	}
}
