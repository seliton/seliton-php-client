<?php

namespace Seliton\Client\HttpClient;

class HttpClient {
	public static function get($url, $params = null)
	{
		return self::request($url, HttpMethod::GET, $params);
	}

	public static function post($url, $json)
	{
		return self::request($url, HttpMethod::POST, $json);
	}

	public static function put($url, $json)
	{
		self::request($url, HttpMethod::PUT, $json);
	}

	public static function delete($url)
	{
		self::request($url, HttpMethod::DELETE);
	}

	protected static function request($url, $method, $params = null)
	{
		if ($method == HttpMethod::GET && !is_null($params)) {
			$url = self::appendUrlEncodedParams($url, $params);
		}
		$result = self::curlExec($url, $method, $params);
		$jsonDecoded = json_decode($result);
		return $jsonDecoded;
	}

	protected static function appendUrlEncodedParams($url, $params)
	{
		$url .= '?';
		foreach ($params as $key => $value) {
			$url .= $key.'='.urldecode($value).'&';
		}
		return substr($url, 0, -1);
	}

	protected static function curlExec($url, $method, $params)
	{
		$curl = curl_init($url);
		switch ($method)
		{
			case HttpMethod::POST:
				curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				break;
			case HttpMethod::PUT;
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				break;
			case HttpMethod::DELETE:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				break;
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}
}

class HttpMethod {
	const GET = 'GET';
	const POST = 'POST';
	const PUT = 'PUT';
	const DELETE = 'DELETE';
}