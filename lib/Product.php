<?php

namespace Seliton\Client;

class Product extends Resource {
	protected static $_name = 'product';
	protected static $namePlural = 'products';
	protected static $fields = array (
		'id',
		'name',
		'description',
		'detailedDescription',
		'pageTitle',
		'metaKeywords',
		'metaDescription',
		'isActive',
		'availabilityStatus',
		'code',
		'barcode',
		'nameXHTML',
		'descriptionXHTML',
		'detailedDescriptionXHTML',
		'homePageFeatured',
		'homePageFeaturedFromCategory',
		'price',
		'distributorPrice',
		'weight',
		'quantity',
		'createdTimestamp',
		'bonusPointsMode',
		'bonusPointsPrice',
		'isNew',
		'featuredStyle',
	);
	protected static $externalFields = array (
		'brandID',
		'availabilityLabelID',
	);
}

class ProductAvailabilityStatus {
	const OUT_OF_STOCK = 'out_of_stock';
	const IN_STOCK = 'in_stock';
}

class ProductBonusPointsMode {
	const MONEY_ONLY = 'money_only';
	const POINTS_AND_MONEY = 'points_and_money';
	const POINTS_ONLY = 'points_only';
}

class ProductFeaturedStyle {
	const NORMAL = 'normal';
	const DOUBLE_WIDTH = 'double_width';
	const DOUBLE_HEIGHT = 'double_height';
	const DOUBLE_DOUBLE = 'double_double';
}
