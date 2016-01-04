<?php

namespace Seliton\Client;

class Seliton {
	public static function setApiUrl($apiUrl)
	{
		Resource::setApiUrl($apiUrl);
	}

	public function attribute()
	{
		return new Attribute($this->apiUrl);
	}

	public function brand()
	{
		return new Brand($this->apiUrl);
	}

	public function category()
	{
		return new Category($this->apiUrl);
	}

	public function customer()
	{
		return new Customer($this->apiUrl);
	}

	public function order()
	{
		return new Order($this->apiUrl);
	}

	public function page()
	{
		return new Page($this->apiUrl);
	}

	public function product()
	{
		return new Product($this->apiUrl);
	}

	public function __construct($apiUrl)
	{
		$this->apiUrl = $apiUrl;
	}
}