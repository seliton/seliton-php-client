<?php

namespace Seliton\Client;

use Seliton\Client\Resource;

class Seliton {
	protected static $resources = array (
		'attribute',
		'brand',
		'category',
		'customer',
		'order',
		'page',
		'product',
		'scriptCode',
	);
	protected static $apiUrlForStaticMethods;
	protected static $accessTokenForStaticMethods;
	protected $apiUrl;
	protected $accessToken;

	public function __construct($apiUrl, $accessToken = null)
	{
		$this->apiUrl = $apiUrl;
		$this->accessToken = $accessToken;
	}

	public function __call($methodName, $arguments)
	{
		if (in_array($methodName, static::$resources)) {
			$resourceClassName = 'Seliton\\Client\\Resource\\'.ucfirst($methodName);
			return new $resourceClassName($this->apiUrl, $this->accessToken);
		} else {
			throw new \Exception("Method '$methodName' not exists");
		}
	}

	public static function setApiUrl($apiUrl)
	{
		static::$apiUrlForStaticMethods = $apiUrl;
	}

	public static function setAccessToken($accessToken)
	{
		static::$accessTokenForStaticMethods = $accessToken;
	}

	public static function __callStatic($methodName, $arguments)
	{
		if (in_array($methodName, static::$resources)) {
			$resourceClassName = 'Seliton\\Client\\Resource\\'.ucfirst($methodName);
			return new $resourceClassName(
				static::$apiUrlForStaticMethods,
				static::$accessTokenForStaticMethods
			);
		} else {
			throw new \Exception("Method '$methodName' not exists");
		}
	}
}
