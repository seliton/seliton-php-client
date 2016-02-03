<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Resource;
	
	class Page extends Resource {
		protected static $_name = 'page';
		protected static $namePlural = 'pages';
		protected static $fields = array (
			'id',
			'title',
			'content',
			'seoTitle',
			'seoKeywords',
			'seoDescription',
			'cssClass',
			'updateTimestamp',
			'isActive',
		);
		protected static $fieldsToApi = array (
			'seoTitle' => 'SEOTitle',
			'seoKeywords' => 'SEOKeywords',
			'seoDescription' => 'SEODescription',
		);
	}
?>