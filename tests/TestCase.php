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
			$tokenUrl = 'http://dev.seliton.com/authorize?client_id=testclient&response_type=code' .
				'&state=xyz&shop=dev-1.myseliton.com&scope=' . implode('%20', $scopes);
			$curl = curl_init($tokenUrl);
			curl_setopt($curl, CURLOPT_POSTFIELDS, array('authorized' => 'Accept'));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_exec($curl);
			$info = curl_getinfo($curl);
			static::$accessToken = substr(
				$info['redirect_url'],
				strlen('http://dev.seliton.com/recent-orders-app?access_token=')
			);
		}

		return static::$accessToken;
	}
}
