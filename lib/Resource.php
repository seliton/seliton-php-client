<?php

namespace Seliton\Client;

class Resource {
	protected static $_name;
	protected static $namePlural;
	protected static $fields;
	protected static $externalFields = array ();
	protected static $fieldsToRest = array ();

	protected static $apiUrlStatic;

	protected $apiUrl = null;

	public function __construct($params)
	{
		if (is_string($params)) {
			$this->apiUrl = $params;
		} else {
			foreach (self::fields() as $field) {
				$paramsField = self::field($field);
				if (property_exists($params, $paramsField)) {
					$this->$field = $this->convertField($field, $params->$paramsField);
				}
			}
			foreach (static::$externalFields as $field) {
				if (property_exists($params, $field)) {
					$this->$field = $params->$field;
				}
			}
		}
	}

	public function json()
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

	public function __call($methodName, $arguments)
	{
		switch ($methodName) {
			case 'create':
				if (isset($arguments[0])) {
					return $this->_create($arguments[0]);
				} else {
					return $this->_create();
				}
				break;
			case 'retrieve':
				return $this->_retrieve($arguments[0]);
				break;
			case 'all':
				if (isset($arguments[0])) {
					return $this->_all($arguments[0]);
				} else {
					return $this->_all();
				}
				break;
			default:
				throw new MemberAccessException('Method ' . $methodName . ' not exists');
		}
	}

	protected function _create($params = array ())
	{
		$resource = static::createWithApiUrl($this->apiUrl(), $params);
		$resource->_setApiUrl($this->apiUrl);
		return $resource;
	}

	public static function __callStatic($methodName, $arguments)
	{
		switch ($methodName) {
			case 'create':
				if (isset($arguments[0])) {
					return static::createStatic($arguments[0]);
				} else {
					return static::createStatic();
				}
				break;
			case 'retrieve':
				return static::retrieveStatic($arguments[0]);
				break;
			case 'all':
				if (isset($arguments[0])) {
					return static::allStatic($arguments[0]);
				} else {
					return static::allStatic();
				}
				break;
			default:
				throw new MemberAccessException('Method ' . $methodName . ' not exists');
		}
	}

	public static function createStatic($params = array ())
	{
		return static::createWithApiUrl(self::apiUrlStatic(), $params);
	}

	protected static function retrieveStatic($id)
	{
		return static::retrieveWithApiUrl(self::apiUrlStatic($id), $id);
	}

	protected function _retrieve($id)
	{
		$resource = static::retrieveWithApiUrl($this->apiUrl($id), $id);
		$resource->_setApiUrl($this->apiUrl);
		return $resource;
	}

	protected function _setApiUrl($apiUrl)
	{
		$this->apiUrl = $apiUrl;
	}

	protected static function allStatic($params = null)
	{
		return static::allWithApiUrl(self::apiUrlStatic(), $params);
	}

	protected function _all($params = null)
	{
		list ($resources, $count) = static::allWithApiUrl($this->apiUrl(), $params);
		foreach ($resources as $resource) {
			$resource->_setApiUrl($this->apiUrl);
		}
		return array ($resources, $count);
	}

	public function save()
	{
		$apiUrl = is_null($this->apiUrl) ?
			self::apiUrlStatic($this->id) : $this->apiUrl($this->id);
		HttpClient::put($apiUrl, $this->json());
	}

	public function delete()
	{
		$apiUrl = is_null($this->apiUrl) ?
			self::apiUrlStatic($this->id) : $this->apiUrl($this->id);
		HttpClient::delete($apiUrl);
	}

	public static function setApiUrl($apiUrl)
	{
		self::$apiUrlStatic = $apiUrl;
	}

	protected function apiUrl($path = '')
	{
		return self::apiUrlWithBase($this->apiUrl, $path);
	}

	protected static function apiUrlStatic($path = '')
	{
		return self::apiUrlWithBase(self::$apiUrlStatic, $path);
	}

	protected static function apiUrlWithBase($baseUrl, $path = '')
	{
		return $baseUrl.self::namePlural()."/$path";
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
		return '\\Seliton\\Client\\'.self::nameFirstUpper();
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

	protected static function createWithApiUrl($apiUrl, $params = array ())
	{
		$resourceClassName = self::className();
		return new $resourceClassName(HttpClient::post($apiUrl, json_encode($params)));
	}

	protected static function retrieveWithApiUrl($apiUrl, $id)
	{
		$jsonDecoded = HttpClient::get($apiUrl);
		$resourceName = self::name();
		if (isset($jsonDecoded->$resourceName)) {
			$resourceClassName = self::className();
			return new $resourceClassName($jsonDecoded->$resourceName);
		} else {
			throw new \Exception($jsonDecoded->error->message);
		}
	}

	protected static function allWithApiUrl($apiUrl, $params = null)
	{
		$jsonDecoded = HttpClient::get($apiUrl, $params);
		$namePlural = self::namePlural();
		if (isset($jsonDecoded->$namePlural)) {
			$resources = array ();
			foreach ($jsonDecoded->$namePlural as $resource) {
				$resourceClassName = self::className();
				$resources[] = new $resourceClassName($resource);
			}
			return array ($resources, $jsonDecoded->_metadata->count);
		} else {
			throw new \Exception($jsonDecoded->error->message);
		}
	}

	protected function convertField($name, $value)
	{
		return $value;
	}
}