<?php

namespace Seliton\Client;

class Resource {
	protected static $_name;
	protected static $namePlural;
	protected static $fields;
	protected static $externalFields = array ();

	private static $apiUrl = 'http://dev-1.myseliton.com/api/v1/';

	public function __construct($params) {
		foreach (self::fields() as $field) {
			$paramsField = self::field($field);
			if (property_exists($params, $paramsField)) {
				$this->$field = $params->$paramsField;
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

	public static function create($params = array ()) {
		$resourceClassName = self::className();
		return new $resourceClassName(HttpClient::post(self::apiUrl(), json_encode($params)));
	}

	public static function retrieve($id) {
		$jsonDecoded = HttpClient::get(self::apiUrl($id));
		$resourceName = self::name();
		if (isset($jsonDecoded->$resourceName)) {
			$resourceClassName = self::className();
			return new $resourceClassName($jsonDecoded->$resourceName);
		} else {
			throw new \Exception($jsonDecoded->error->message);
		}
	}

	public static function all($params = null) {
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

	private static function apiUrl($path = '')
	{
		return self::$apiUrl.self::namePlural()."/$path";
	}

	private static function name()
	{
		return static::$_name;
	}

	private static function nameFirstUpper()
	{
		return ucfirst(self::name());
	}

	private static function className()
	{
		return '\\Seliton\\Client\\'.self::nameFirstUpper();
	}

	private static function namePlural()
	{
		return static::$namePlural;
	}

	private static function fields()
	{
		return static::$fields;
	}

	private static function field($name)
	{
		if (substr($name, 0, 3) == 'seo') {
			return self::name().'SEO'.substr($name, 3);
		}

		return self::name().ucfirst($name);
	}
}