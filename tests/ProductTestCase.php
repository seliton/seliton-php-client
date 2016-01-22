<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Tests;
	
	use
		Seliton\Client\Seliton,
		Seliton\Client\Resource\Enum;
	
	require_once dirname(__FILE__).'/TestCase.php';
	
	class ProductTestCase extends TestCase
	{
		protected function setUp()
		{
			$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/', static::getAccessToken());
			$this->product = $seliton->product();
			
			// Remove existing test products
			list ($products) = $this->product->all(array ('nameContains' => 'Test'));
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
			$availabilityStatus = Enum\ProductAvailabilityStatus::IN_STOCK;
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
			$bonusPointsMode = Enum\ProductBonusPointsMode::MONEY_ONLY;
			$bonusPointsPrice = null;
			$isNew = false;
			$featuredStyle = Enum\ProductFeaturedStyle::NORMAL;
			$images = array (
				array (
					'productImage' => 'image_1.jpg',
					'productImageFeaturedStyle' => Enum\ProductFeaturedStyle::NORMAL
				),
				array (
					'productImage' => 'image_2.jpg',
					'productImageFeaturedStyle' => Enum\ProductFeaturedStyle::DOUBLE_WIDTH
				),
			);
			
			$product = $this->product->create(
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
					'productImages' => $images,
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
			
			for ($i = 0; $i < count($images); $i++) {
				$productImage = $product->images[$i];
				$this->assertEquals(
					$images[$i]['productImage'],
					$productImage['productImage']
				);
				$this->assertEquals(
					$images[$i]['productImageFeaturedStyle'],
					$productImage['productImageFeaturedStyle']
				);
			}
		}
		
		public function testRetrieve()
		{
			$name = 'Test';
			$code = 'test';
			$categories = array (array ('categoryID' => 4));
			
			$product = $this->product->create(
				array (
					'productName' => array ('en' => $name),
					'productCode' => $code,
					'productCategories' => $categories,
				)
			);
			
			$productRetrieved = $this->product->retrieve($product->id);
			
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
			$images = array (
				array (
					'productImage' => 'image_1.jpg',
					'productImageFeaturedStyle' => Enum\ProductFeaturedStyle::NORMAL
				),
				array (
					'productImage' => 'image_2.jpg',
					'productImageFeaturedStyle' => Enum\ProductFeaturedStyle::NORMAL
				),
			);
			
			$product = $this->product->create(
				array (
					'productName' => array ('en' => $name),
					'productCode' => $code,
					'productCategories' => $categories,
					'productImages' => $images,
				)
			);
			
			$productRetrieved = $this->product->retrieve($product->id);
			$productRetrieved->code = 'test_save';
			$productRetrieved->categories = array (
				array ('categoryID' => 1),
				array ('categoryID' => 3)
			);
			$productRetrieved->images = array (
				array (
					'productImage' => 'updated_image_1.jpg',
					'productImageFeaturedStyle' => Enum\ProductFeaturedStyle::DOUBLE_WIDTH
				),
				array (
					'productImage' => 'updated_image_2.jpg',
					'productImageFeaturedStyle' => Enum\ProductFeaturedStyle::DOUBLE_WIDTH
				),
			);
			$productRetrieved->save();
			
			$productSaved = $this->product->retrieve($productRetrieved->id);
			
			$this->assertEquals($productRetrieved->code, $productSaved->code);
			$this->assertEquals($productRetrieved->categories, $productSaved->categories);
			for ($i = 0; $i < count($productRetrieved->images); $i++) {
				$productSavedImage = $productSaved->images[$i];
				$this->assertEquals(
					$productRetrieved->images[$i]['productImage'],
					$productSavedImage['productImage']
				);
				$this->assertEquals(
					$productRetrieved->images[$i]['productImageFeaturedStyle'],
					$productSavedImage['productImageFeaturedStyle']
				);
			}
		}
		
		public function testDelete()
		{
			$name = 'Test';
			$code = 'test';
			$categories = array (array ('categoryID' => 4));
			
			$product = $this->product->create(
				array (
					'productName' => array ('en' => $name),
					'productCode' => $code,
					'productCategories' => $categories,
				)
			);
			
			$productRetrieved = $this->product->retrieve($product->id);
			$productRetrieved->delete();
			
			$this->setExpectedException('Exception');
			$productNonExistent = $this->product->retrieve($product->id);
		}
		
		public function testAll()
		{
			for ($i = 1; $i <= 3; $i++) {
				$this->product->create(
					array (
						'productName' => array ('en' => "Test $i"),
						'productCode' => "test_$i",
						'productCategories' => array (array ('categoryID' => 4)),
					)
				);
			}
			
			list ($products, $count) = $this->product->all(array (
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
?>