<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client;
	
	use Seliton\Client\Resource;
	
	/**
	 * Seliton class
	 * 
	 * @method static Resource\Attribute attribute() Attribute resource
	 * @method static Resource\Brand brand() Brand resource
	 * @method static Resource\Category category() Category resource
	 * @method static Resource\Customer customer() Customer resource
	 * @method static Resource\Order order() Order resource
	 * @method static Resource\Page page() Page resource
	 * @method static Resource\Product product() Product resource
	 * @method static Resource\ScriptCode scriptCode() Script Code resource
	 * 
	 * @package Seliton\Client
	 */
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
?>