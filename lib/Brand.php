<?php

namespace Seliton\Client;

class Brand extends Resource {
	protected static $_name = 'brand';
	protected static $namePlural = 'brands';
	protected static $fields = array (
		'id',
		'name',
		'description',
		'seoTitle',
		'seoKeywords',
		'seoDescription',
		'website',
		'image',
		'imageWidth',
		'imageHeight',
		'productCount',
		'sort',
	);
}