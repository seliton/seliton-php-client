<?php

namespace Seliton\Client;

use Seliton\Client\Resource;

class Seliton {

	public function __construct($apiUrl)
	{
		$this->apiUrl = $apiUrl;
	}

	public function attribute()
	{
		return new Resource\Attribute($this->apiUrl);
	}

	public function brand()
	{
		return new Resource\Brand($this->apiUrl);
	}

	public function category()
	{
		return new Resource\Category($this->apiUrl);
	}

	public function customer()
	{
		return new Resource\Customer($this->apiUrl);
	}

	public function order()
	{
		return new Resource\Order($this->apiUrl);
	}

	public function page()
	{
		return new Resource\Page($this->apiUrl);
	}

	public function product()
	{
		return new Resource\Product($this->apiUrl);
	}
}
