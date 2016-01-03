<?php

namespace Seliton\Client;

class Seliton {
	public static function setApiUrl($apiUrl)
	{
		Resource::setApiUrl($apiUrl);
	}

	public static function factory($apiUrl)
	{
		return new Seliton($apiUrl);
	}

	public function page()
	{
		return new Page($this->apiUrl);
	}

	public function __construct($apiUrl)
	{
		$this->apiUrl = $apiUrl;
	}
}