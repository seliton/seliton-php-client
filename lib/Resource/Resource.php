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
		protected static $fieldsToApi = array ();
		
		/**
		 * @var integer Resource ID
		 */
		public $id;
		
		protected $apiUrl = null;
		protected $accessToken;
		
		/**
		 * Resource constructor
		 * 
		 * @param string $apiUrl Base API URL
		 * @param string $accessToken JSON Web Token
		 */
		public function __construct($apiUrl, $accessToken)
		{
			$this->apiUrl = $apiUrl;
			$this->accessToken = $accessToken;
		}
		
		/**
		 * Set Resource fields from JSON decoded
		 * 
		 * @param mixed $jsonDecoded JSON decoded
		 */
		protected function setFieldsFromJsonDecoded($jsonDecoded)
		{
			foreach (static::$fields as $field) {
				$paramsField = self::fieldForApi($field);
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
		
		/**
		 * Resource array representation
		 * 
		 * @return array
		 */
		public function toArray()
		{
			$result = array ();
			foreach (static::$fields as $field) {
				$apiField = self::fieldForApi($field);
				if (isset($this->$field)) {
					$result[$apiField] = $this->$field;
				}
			}
			return $result;
		}
		
		/**
		 * Create Resource via API
		 * 
		 * @param array $params POST parameters
		 * @return Resource
		 */
		public function create($params = array ())
		{
			$jsonDecoded = HttpClient::post($this->apiUrl(), $params);
			
			$resourceClassName = self::className();
			/** @var $resource \Seliton\Client\Resource\Resource */
			$resource = new $resourceClassName($this->apiUrl, $this->accessToken);
			$resource->setFieldsFromJsonDecoded($jsonDecoded);
			return $resource;
		}
		
		/**
		 * Retrieve Resource via API
		 * 
		 * @param int $id Resource ID
		 * @return Resource
		 * @throws \Exception On API error
		 */
		public function retrieve($id)
		{
			$jsonDecoded = HttpClient::get($this->apiUrl($id));
			
			$resourceName = static::$_name;
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
		
		/**
		 * Retrieve Resources via API
		 * 
		 * @param array $params GET parameters
		 * @return array Resources and Resources count tuple
		 * @throws \Exception On API error
		 */
		public function all($params = array ())
		{
			$jsonDecoded = HttpClient::get($this->apiUrl(), $params);
			$namePlural = static::$namePlural;
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
		
		/**
		 * Save Resource via API
		 * 
		 * @throws \Exception On API error
		 */
		public function save()
		{
			$apiUrl = $this->apiUrl($this->id);
			$jsonDecoded = HttpClient::put($apiUrl, $this->toArray());
			if (isset($jsonDecoded->error)) {
				throw new \Exception($jsonDecoded->error->message);
			}
		}
		
		/**
		 * Update Resource via API
		 * 
		 * @throws \Exception On API error
		 * @param $fields array Resource fields (name => value) to be updated
		 * @return Resource
		 */
		public function update($fields)
		{
			$apiUrl = $this->apiUrl($fields[static::fieldForApi('Id')]);
			$jsonDecoded = HttpClient::put($apiUrl, $fields);
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
		
		/**
		 * Delete Resource via API
		 */
		public function delete()
		{
			$apiUrl = $this->apiUrl($this->id);
			HttpClient::delete($apiUrl);
		}
		
		/**
		 * API Url for given path
		 * 
		 * @param string $path
		 * @return string
		 */
		protected function apiUrl($path = '')
		{
			return $this->apiUrl.static::$namePlural."/$path?access_token={$this->accessToken}";
		}
		
		/**
		 * Resource name with first letter uppercase
		 * 
		 * @return string
		 */
		protected static function nameFirstUpper()
		{
			return ucfirst(static::$_name);
		}
		
		/**
		 * Resource full class name (with namespace)
		 * 
		 * @return string
		 */
		protected static function className()
		{
			return '\\Seliton\\Client\\Resource\\'.self::nameFirstUpper();
		}
		
		/**
		 * Field name for API
		 * 
		 * @param string $name Field name
		 * @return string
		 */
		protected static function fieldForApi($name)
		{
			if (isset(static::$fieldsToApi[$name])) {
				return static::$_name.static::$fieldsToApi[$name];
			}
			
			return static::$_name.ucfirst($name);
		}
		
		/**
		 * Convert Resource's custom fields values (if overridden)
		 * 
		 * @param string $name Field name
		 * @param mixed $value Field value
		 * @return mixed Converted value
		 */
		protected function convertField($name, $value)
		{
			return $value;
		}
	}
?>