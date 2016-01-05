<?php

namespace Seliton\Client\Resource;

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
		'categories',
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
		'images',
	);
	protected static $externalFields = array (
		'brandID',
		'availabilityLabelID',
	);

	protected function convertField($name, $value)
	{
		if ($name == 'categories') {
			$categories = array ();
			foreach ($value as $category) {
				$categories[] = array ('categoryID' => $category->categoryID);
			}
			return $categories;
		}
		if ($name == 'images') {
			$images = array ();
			foreach ($value as $image) {
				$images[] = array (
					'productImageID' => $image->productImageID,
					'productImage' => $image->productImage,
					'productImageWidth' => $image->productImageWidth,
					'productImageHeight' => $image->productImageHeight,
					'productImageSize' => $image->productImageSize,
					'productImageSort' => $image->productImageSort,
					'productImageFeaturedStyle' => $image->productImageFeaturedStyle,
				);
			}
			return $images;
		}
		return parent::convertField($name, $value);
	}
}
