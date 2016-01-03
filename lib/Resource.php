<?php

namespace Seliton\Client;

class Resource {
	protected static $_name;
	protected static $namePlural;
	protected static $fields;
	protected static $externalFields = array ();
	protected static $fieldsToRest = array ();

	protected static $apiUrl;

	public function __construct($params)
	{
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

	public static function create($params = array ())
	{
		$resourceClassName = self::className();
		return new $resourceClassName(HttpClient::post(self::apiUrl(), json_encode($params)));
	}

	public static function retrieve($id)
	{
		$jsonDecoded = HttpClient::get(self::apiUrl($id));
		$resourceName = self::name();
		if (isset($jsonDecoded->$resourceName)) {
			$resourceClassName = self::className();
			return new $resourceClassName($jsonDecoded->$resourceName);
		} else {
			throw new \Exception($jsonDecoded->error->message);
		}
	}

	public static function all($params = null)
	{
		$jsonDecoded = HttpClient::get(self::apiUrl(), $params	);
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

	public function save()
	{
		HttpClient::put(self::apiUrl($this->id), $this->json());
	}

	public function delete()
	{
		HttpClient::delete(self::apiUrl($this->id));
	}

	public static function setApiUrl($apiUrl)
	{
		self::$apiUrl = $apiUrl;
	}

	protected static function apiUrl($path = '')
	{
		return self::$apiUrl.self::namePlural()."/$path";
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