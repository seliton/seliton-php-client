<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\HttpClient;
	
	use Seliton\Client\HttpClient\Enum\HttpMethod;
	
	class HttpClient {
		/**
		 * HTTP GET request
		 * 
		 * @param string $url Request URL
		 * @param array $params GET parameters
		 * @return mixed JSON decoded result
		 */
		public static function get($url, $params = array ())
		{
			return self::request($url, HttpMethod::GET, $params);
		}
		
		/**
		 * HTTP POST request
		 * 
		 * @param string $url Request URL
		 * @param array $params POST parameters
		 * @return mixed JSON decoded result
		 */
		public static function post($url, $params)
		{
			return self::request($url, HttpMethod::POST, $params);
		}
		
		/**
		 * HTTP PUT request
		 * 
		 * @param string $url Request URL
		 * @param array $params POST parameters
		 * @return mixed JSON decoded result
		 */
		public static function put($url, $params)
		{
			return self::request($url, HttpMethod::PUT, $params);
		}
		
		/**
		 * HTTP DELETE request
		 * 
		 * @param $url Request URL
		 */
		public static function delete($url)
		{
			self::request($url, HttpMethod::DELETE);
		}
		
		/**
		 * HTTP request
		 * 
		 * @param string $url
		 * @param int $method HttpMethod class constant
		 * @param array $params GET or POST parameters
		 * @return mixed JSON decoded result
		 */
		protected static function request($url, $method, $params = array ())
		{
			$result = self::curlExec($url, $method, $params);
			$jsonDecoded = json_decode($result);
			return $jsonDecoded;
		}
		
		/**
		 * Add url-encoded params at the end of URL
		 * 
		 * @param string $url Initial URL
		 * @param array $params Parameters
		 * @return string URL with appended parameters
		 */
		protected static function appendUrlEncodedParams($url, $params)
		{
			if (!empty($params)) {
				$url .= strpos($url, '?') === false ? '?' : '&';
				$url .= http_build_query($params);
			}
			return $url;
		}
		
		/**
		 * Curl based HTTP request
		 * 
		 * @param string $url
		 * @param int $method HttpMethod class constant
		 * @param array $params GET or POST parameters
		 * @return mixed $params JSON decoded result
		 */
		protected static function curlExec($url, $method, $params)
		{
			if ($method == HttpMethod::GET) {
				$url = self::appendUrlEncodedParams($url, $params);
			}
			$curl = curl_init($url);
			switch ($method)
			{
				case HttpMethod::POST:
					curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
					curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					break;
				case HttpMethod::PUT;
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
					curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
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
?>