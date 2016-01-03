<?php

namespace Seliton\Client;

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
	protected static $fieldsToRest = array (
		'seoTitle' => 'SEOTitle',
		'seoKeywords' => 'SEOKeywords',
		'seoDescription' => 'SEODescription',
	);
}
