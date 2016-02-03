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
		
		/**
		 * Seliton constructor
		 * 
		 * @param $apiUrl Base API URL
		 * @param string $accessToken JSON Web Token
		 */
		public function __construct($apiUrl, $accessToken)
		{
			$this->apiUrl = $apiUrl;
			$this->accessToken = $accessToken;
		}
		
		/**
		 * Non-static magic methods for available Resources
		 * 
		 * @param string $methodName
		 * @param array $arguments
		 * @return mixed
		 * @throws \Exception
		 */
		public function __call($methodName, $arguments)
		{
			if (in_array($methodName, static::$resources)) {
				$resourceClassName = 'Seliton\\Client\\Resource\\'.ucfirst($methodName);
				return new $resourceClassName($this->apiUrl, $this->accessToken);
			} else {
				throw new \Exception("Method '$methodName' not exists");
			}
		}
		
		/**
		 * Set base API URL globally
		 * 
		 * @param string $apiUrl 
		 */
		public static function setApiUrl($apiUrl)
		{
			static::$apiUrlForStaticMethods = $apiUrl;
		}
		
		/**
		 * Set JSON Web Token globally
		 * 
		 * @param string $accessToken
		 */
		public static function setAccessToken($accessToken)
		{
			static::$accessTokenForStaticMethods = $accessToken;
		}
		
		/**
		 * Static magic methods for available Resources
		 * 
		 * @param string $methodName
		 * @param array $arguments
		 * @return mixed
		 * @throws \Exception
		 */
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