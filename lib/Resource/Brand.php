<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Resource;
	
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
		protected static $fieldsToRest = array (
			'seoTitle' => 'SEOTitle',
			'seoKeywords' => 'SEOKeywords',
			'seoDescription' => 'SEODescription',
		);
	}
?>