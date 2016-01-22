<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Resource;
	
	class Category extends Resource {
		protected static $_name = 'category';
		protected static $namePlural = 'categories';
		protected static $fields = array (
			'id',
			'name',
			'description',
			'seoTitle',
			'seoKeywords',
			'seoDescription',
			'webPosName',
			'parentID',
			'productCount',
			'deepProductCount',
			'originalImage',
			'image',
			'imageWidth',
			'imageHeight',
			'status',
			'featured',
			'includeProductsFromSubs',
			'cssClass',
			'webPosActive',
			'webPosPosition',
			'webPosButtonColor',
			'webPosButtonTextColor',
			'iconImage',
			'iconImageWidth',
			'iconImageHeight',
			'sort',
		);
		protected static $fieldsToRest = array (
			'seoTitle' => 'SEOTitle',
			'seoKeywords' => 'SEOKeywords',
			'seoDescription' => 'SEODescription',
		);
	}
?>