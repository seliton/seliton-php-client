<?php

namespace Seliton\Client\Resource;

use Seliton\Client\HttpClient;

class Resource {
	protected static $_name;
	protected static $namePlural;
	protected static $fields;
	protected static $externalFields = array ();
	protected static $fieldsToRest = array ();

	protected $apiUrl = null;

	public function __construct($apiUrl)
	{
		$this->apiUrl = $apiUrl;
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
		$resource = new $resourceClassName($this->apiUrl);
		$resource->setFieldsFromJsonDecoded($jsonDecoded);
		return $resource;
	}

	public function retrieve($id)
	{
		$jsonDecoded = HttpClient::get($this->apiUrl($id));

		$resourceName = self::name();
		if (isset($jsonDecoded->$resourceName)) {
			$resourceClassName = self::className();
			$resource = new $resourceClassName($this->apiUrl);
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
				$resource = new $resourceClassName($this->apiUrl);
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
		HttpClient::put($apiUrl, $this->toJson());
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
