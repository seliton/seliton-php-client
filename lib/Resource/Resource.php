<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Resource;
	
	use Seliton\Client\HttpClient\HttpClient;
	
	class Resource {
		protected static $_name;
		protected static $namePlural;
		protected static $fields;
		protected static $externalFields = array ();
		protected static $fieldsToRest = array ();
		
		/**
		 * @var integer Resource ID
		 */
		public $id;
		
		protected $apiUrl = null;
		protected $accessToken;
		
		public function __construct($apiUrl, $accessToken)
		{
			$this->apiUrl = $apiUrl;
			$this->accessToken = $accessToken;
		}
		
		protected function setFieldsFromJsonDecoded($jsonDecoded)
		{
			foreach (self::fields() as $field) {
				$paramsField = self::field($field);
				if (property_exists($jsonDecoded, $paramsField)) {
					$this->$field = $this->convertField($field, $jsonDecoded->$paramsField);
				}
			}
			foreach (static::$externalFields as $field) {
				if (property_exists($jsonDecoded, $field)) {
					$this->$field = $jsonDecoded->$field;
				}
			}
		}
		
		public function toJson()
		{
			$result = array ();
			foreach (self::fields() as $field) {
				$apiField = self::field($field);
				if (isset($this->$field)) {
					$result[$apiField] = $this->$field;
				}
			}
			return json_encode($result);
		}
		
		public function create($params = array ())
		{
			$jsonDecoded = HttpClient::post($this->apiUrl(), json_encode($params));
			
			$resourceClassName = self::className();
			/** @var $resource \Seliton\Client\Resource\Resource */
			$resource = new $resourceClassName($this->apiUrl, $this->accessToken);
			$resource->setFieldsFromJsonDecoded($jsonDecoded);
			return $resource;
		}
		
		public function retrieve($id)
		{
			$jsonDecoded = HttpClient::get($this->apiUrl($id));
			
			$resourceName = self::name();
			if (isset($jsonDecoded->$resourceName)) {
				$resourceClassName = self::className();
				/** @var $resource \Seliton\Client\Resource\Resource */
				$resource = new $resourceClassName($this->apiUrl, $this->accessToken);
				$resource->setFieldsFromJsonDecoded($jsonDecoded->$resourceName);
			} else {
				throw new \Exception($jsonDecoded->error->message);
			}
			return $resource;
		}
		
		public function all($params = null)
		{
			$jsonDecoded = HttpClient::get($this->apiUrl(), $params);
			$namePlural = self::namePlural();
			if (isset($jsonDecoded->$namePlural)) {
				$resources = array ();
				foreach ($jsonDecoded->$namePlural as $resourceJsonDecoded) {
					$resourceClassName = self::className();
					/** @var $resource \Seliton\Client\Resource\Resource */
					$resource = new $resourceClassName($this->apiUrl, $this->accessToken);
					$resource->setFieldsFromJsonDecoded($resourceJsonDecoded);
					$resources[] = $resource;
				}
				$resourcesCount = $jsonDecoded->_metadata->count;
			} else {
				throw new \Exception($jsonDecoded->error->message);
			}
			return array ($resources, $resourcesCount);
		}
		
		public function save()
		{
			$apiUrl = $this->apiUrl($this->id);
			$jsonDecoded = HttpClient::put($apiUrl, $this->toJson());
			if (isset($jsonDecoded->error)) {
				throw new \Exception($jsonDecoded->error->message);
			}
		}
		
		/**
		 * Update Resource
		 * 
		 * @throws \Exception
		 * @param $fields array Resource fields (name => value) to be updated
		 * @return Resource
		 */
		public function update($fields)
		{
			$apiUrl = $this->apiUrl($fields[static::field('Id')]);
			$jsonDecoded = HttpClient::put($apiUrl, json_encode($fields));
			if (isset($jsonDecoded->error)) {
				throw new \Exception($jsonDecoded->error->message);
			}
			$resourceClassName = self::className();
			/** @var $resource \Seliton\Client\Resource\Resource */
			$resource = new $resourceClassName($this->apiUrl, $this->accessToken);
			$resourceName = static::$_name;
			$resource->setFieldsFromJsonDecoded($jsonDecoded->$resourceName);
			return $resource;
		}
		
		public function delete()
		{
			$apiUrl = $this->apiUrl($this->id);
			HttpClient::delete($apiUrl);
		}
		
		protected function apiUrl($path = '')
		{
			return $this->apiUrl.self::namePlural()."/$path?access_token={$this->accessToken}";
		}
		
		protected static function name()
		{
			return static::$_name;
		}
		
		protected static function nameFirstUpper()
		{
			return ucfirst(self::name());
		}
		
		protected static function className()
		{
			return '\\Seliton\\Client\\Resource\\'.self::nameFirstUpper();
		}
		
		protected static function namePlural()
		{
			return static::$namePlural;
		}
		
		protected static function fields()
		{
			return static::$fields;
		}
		
		protected static function field($name)
		{
			if (isset(static::$fieldsToRest[$name])) {
				return self::name().static::$fieldsToRest[$name];
			}
			
			return self::name().ucfirst($name);
		}
		
		protected function convertField($name, $value)
		{
			return $value;
		}
	}
?>