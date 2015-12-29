<?php

namespace Seliton\Client;

class Category extends Resource {
	const STATUS_VISIBLE = 'visible';
	const STATUS_HIDDEN = 'hidden';

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
}