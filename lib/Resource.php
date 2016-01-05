<?php

namespace Seliton\Client;

class Resource {
	protected static $_name;
	protected static $namePlural;
	protected static $fields;
	protected static $externalFields = array ();
	protected static $fieldsToRest = array ();

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

	public function create($params = array ())
	{		
		$jsonDecoded = HttpClient::post($this->apiUrl(), json_encode($params));

		$resourceClassName = self::className();
		$resource = new $resourceClassName($jsonDecoded);
		$resource->setApiUrl($this->apiUrl);
		return $resource;
	}

	public function retrieve($id)
	{
		$jsonDecoded = HttpClient::get($this->apiUrl($id));

		$resourceName = self::name();
		if (isset($jsonDecoded->$resourceName)) {
			$resourceClassName = self::className();
			$resource = new $resourceClassName($jsonDecoded->$resourceName);
		} else {
			throw new \Exception($jsonDecoded->error->message);
		}
		$resource->setApiUrl($this->apiUrl);
		return $resource;
	}

	protected function setApiUrl($apiUrl)
	{
		$this->apiUrl = $apiUrl;
	}

	public function all($params = null)
	{
		$jsonDecoded = HttpClient::get($this->apiUrl(), $params);
		$namePlural = self::namePlural();
		if (isset($jsonDecoded->$namePlural)) {
			$resources = array ();
			foreach ($jsonDecoded->$namePlural as $resource) {
				$resourceClassName = self::className();
				$resources[] = new $resourceClassName($resource);
			}
			$resourcesCount = $jsonDecoded->_metadata->count;
		} else {
			throw new \Exception($jsonDecoded->error->message);
		}
		foreach ($resources as $resource) {
			$resource->setApiUrl($this->apiUrl);
		}
		return array ($resources, $resourcesCount);
	}

	public function save()
	{
		$apiUrl = $this->apiUrl($this->id);
		HttpClient::put($apiUrl, $this->json());
	}

	public function delete()
	{
		$apiUrl = $this->apiUrl($this->id);
		HttpClient::delete($apiUrl);
	}

	protected function apiUrl($path = '')
	{
		return $this->apiUrl.self::namePlural()."/$path";
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

	protected function convertField($name, $value)
	{
		return $value;
	}
}
