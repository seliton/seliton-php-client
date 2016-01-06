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
	);
	protected static $apiUrlForStaticMethods;
	protected $apiUrl;

	public function __construct($apiUrl)
	{
		$this->apiUrl = $apiUrl;
	}

	public function __call($methodName, $arguments)
	{
		if (in_array($methodName, static::$resources)) {
			$resourceClassName = 'Seliton\\Client\\Resource\\'.ucfirst($methodName);
			return new $resourceClassName($this->apiUrl);
		} else {
			throw new \Exception("Method '$methodName' not exists");
		}
	}

	public static function setApiUrl($apiUrl)
	{
		static::$apiUrlForStaticMethods = $apiUrl;
	}

	public static function __callStatic($methodName, $arguments)
	{
		if (in_array($methodName, static::$resources)) {
			$resourceClassName = 'Seliton\\Client\\Resource\\'.ucfirst($methodName);
			return new $resourceClassName(static::$apiUrlForStaticMethods);
		} else {
			throw new \Exception("Method '$methodName' not exists");
		}
	}
}
