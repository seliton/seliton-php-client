<?php

namespace Seliton\Client;

class ProductTestCase extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		// Remove existing test products
		list ($products) = Product::all(array ('nameContains' => 'Test'));
		foreach ($products as $product) {
			$product->delete();
		}
	}

	public function testCreate()
	{
		$name = array ('en' => 'Test');
		$description = array ('en' => 'Test Description');
		$detailedDescription = array ('en' => 'Test Detailed Description');
		$pageTitle = array ('en' => 'Test Page Tile');
		$metaKeywords = array ('en' => 'Test Meta Keywords');
		$metaDescription = array ('en' => 'Test Meta Description');
		$brandID = 1;
		$isActive = true;
		$availabilityStatus = ProductAvailabilityStatus::IN_STOCK;
		$availabilityLabelID = null;
		$code = 'test';
		$barcode = null;
		$nameXHTML = false;
		$descriptionXHTML = false;
		$detailedDescriptionXHTML = false;
		$categories = array (
			array ('categoryID' => 4),
			array ('categoryID' => 2)
		);
		$homePageFeatured = false;
		$homePageFeaturedFromCategory = false;
		$price = 500;
		$distributorPrice = 300;
		$weight = 4;
		$quantity = 6;
		$bonusPointsMode = ProductBonusPointsMode::MONEY_ONLY;
		$bonusPointsPrice = null;
		$isNew = false;
		$featuredStyle = ProductFeaturedStyle::NORMAL;
		
		$product = Product::create(
			array (
				'productName' => $name,
				'productDescription' => $description,
				'productDetailedDescription' => $detailedDescription,
				'productPageTitle' => $pageTitle,
				'productMetaKeywords' => $metaKeywords,
				'productMetaDescription' => $metaDescription,
				'brandID' => $brandID,
				'productIsActive' => $isActive,
				'productAvailabilityStatus' => $availabilityStatus,
				'availabilityLabelID' => $availabilityLabelID,
				'productCode' => $code,
				'productBarcode' => $barcode,
				'productNameXHTML' => $nameXHTML,
				'productDescriptionXHTML' => $descriptionXHTML,
				'productDetailedDescriptionXHTML' => $detailedDescriptionXHTML,
				'productCategories' => $categories,
				'productHomePageFeatured' => $homePageFeatured,
				'productHomePageFeaturedFromCategory' => $homePageFeaturedFromCategory,
				'productPrice' => $price,
				'productDistributorPrice' => $distributorPrice,
				'productWeight' => $weight,
				'productQuantity' => $quantity,
				'productBonusPointsMode' => $bonusPointsMode,
				'productBonusPointsPrice' => $bonusPointsPrice,
				'productIsNew' => $isNew,
				'productFeaturedStyle' => $featuredStyle,
			)
		);

		$this->assertNotNull($product->id);

		foreach (array (
			'name',
			'description',
			'detailedDescription',
			'pageTitle',
			'metaKeywords',
			'metaDescription',
			) as $mlField) {
			$this->assertEquals(${$mlField}['en'], $product->{$mlField}->EN);
		}
		foreach (array (
			'brandID',
			'isActive',
			'availabilityStatus',
			'availabilityLabelID',
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
			'bonusPointsMode',
			'bonusPointsPrice',
			'isNew',
			'featuredStyle',
			) as $field) {
			$this->assertEquals($$field, $product->$field);
		}
	}

	public function testRetrieve()
	{
		$name = 'Test';
		$code = 'test';
		$categories = array (array ('categoryID' => 4));

		$product = Product::create(
			array (
				'productName' => array ('en' => $name),
				'productCode' => $code,
				'productCategories' => $categories,
			)
		);

		$productRetrieved = Product::retrieve($product->id);

		$this->assertEquals($name, $productRetrieved->name->EN);
		$this->assertEquals($code, $productRetrieved->code);
	}

	public function testSave()
	{
		$name = 'Test';
		$code = 'test';
		$categories = array (
			array ('categoryID' => 4),
			array ('categoryID' => 2)
		);

		$product = Product::create(
			array (
				'productName' => array ('en' => $name),
				'productCode' => $code,
				'productCategories' => $categories,
			)
		);

		$productRetrieved = Product::retrieve($product->id);
		$productRetrieved->code = 'test_save';
		$productRetrieved->categories = array (
			array ('categoryID' => 1),
			array ('categoryID' => 3)
		);
		$productRetrieved->save();

		$productSaved = Product::retrieve($productRetrieved->id);

		$this->assertEquals($productRetrieved->code, $productSaved->code);
		$this->assertEquals($productRetrieved->categories, $productSaved->categories);
	}

	public function testDelete()
	{
		$name = 'Test';
		$code = 'test';
		$categories = array (array ('categoryID' => 4));

		$product = Product::create(
			array (
				'productName' => array ('en' => $name),
				'productCode' => $code,
				'productCategories' => $categories,
			)
		);

		$productRetrieved = Product::retrieve($product->id);
		$productRetrieved->delete();

		$this->setExpectedException('Exception');
		$productNonExistent = Product::retrieve($product->id);
	}

	public function testAll()
	{
		for ($i = 1; $i <= 3; $i++) {
			Product::create(
				array (
					'productName' => array ('en' => "Test $i"),
					'productCode' => "test_$i",
					'productCategories' => array (array ('categoryID' => 4)),
				)
			);
		}

		list ($products, $count) = Product::all(array (
			'nameContains' => 'Test',
			'limit' => 2,
			'offset' => 1,
			'fields' => 'productId,productName'
		));

		$this->assertEquals(3, $count);
		$this->assertEquals(2, count($products));

		for ($j = 1; $j <= 2; $j++) {
			$this->assertEquals('Test '.($j + 1), $products[$j - 1]->name->EN);
			$this->assertTrue(!isset($products[$j - 1]->code));
		}
	}
}
