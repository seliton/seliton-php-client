<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Tests;
	
	use
		Seliton\Client\Seliton,
		Seliton\Client\Resource\Enum;
	
	require_once dirname(__FILE__).'/TestCase.php';
	
	class CategoryTestCase extends TestCase
	{
		protected function setUp()
		{
			$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/', static::getAccessToken());
			$this->category = $seliton->category();
		}
		
		public function testCreate()
		{
			$name = array ('en' => 'Category');
			$description = array ('en' => 'Description');
			$seoTitle = array ('en' => 'SEO Title');
			$seoKeywords = array ('en' => 'SEO Keywords');
			$seoDescription = array ('en' => 'SEO Description');
			$webPosName = array ('en' => 'Web Pos Name');
			$parentID = 1;
			$originalImage = 'original.jpg';
			$image = 'category.jpg';
			$status = Enum\CategoryStatus::VISIBLE;
			$featured = false;
			$includeProductsFromSubs = true;
			$cssClass = 'category';
			$webPosActive = true;
			$webPosPosition = 500;
			$webPosButtonColor = 'red';
			$webPosButtonTextColor = 'green';
			$iconImage = 'icon.jpg';
			$sort = 1000;
			
			$category = $this->category->create(
				array (
					'categoryName' => $name,
					'categoryDescription' => $description,
					'categorySEOTitle' => $seoTitle,
					'categorySEOKeywords' => $seoKeywords,
					'categorySEODescription' => $seoDescription,
					'categoryWebPosName' => $webPosName,
					'categoryParentID' => $parentID,
					'categoryOriginalImage' => $originalImage,
					'categoryImage' => $image,
					'categoryStatus' => $status,
					'categoryFeatured' => $featured,
					'categoryIncludeProductsFromSubs' => $includeProductsFromSubs,
					'categoryCssClass' => $cssClass,
					'categoryWebPosActive' => $webPosActive,
					'categoryWebPosPosition' => $webPosPosition,
					'categoryWebPosButtonColor' => $webPosButtonColor,
					'categoryWebPosButtonTextColor' => $webPosButtonTextColor,
					'categoryIconImage' => $iconImage,
					'categorySort' => $sort,
				)
			);
			
			$this->assertNotNull($category->id);
			$this->assertEquals($name['en'], $category->name->EN);
			$this->assertEquals($description['en'], $category->description->EN);
			$this->assertEquals($seoTitle['en'], $category->seoTitle->EN);
			$this->assertEquals($seoKeywords['en'], $category->seoKeywords->EN);
			$this->assertEquals($seoDescription['en'], $category->seoDescription->EN);
			$this->assertEquals($webPosName['en'], $category->webPosName->EN);
			foreach (array (
								 'parentID',
								 'originalImage',
								 'image',
								 'status',
								 'featured',
								 'includeProductsFromSubs',
								 'cssClass',
								 'webPosActive',
								 'webPosPosition',
								 'webPosButtonColor',
								 'webPosButtonTextColor',
								 'iconImage',
								 'sort'
							 ) as $field) {
				$this->assertEquals($$field, $category->$field);
			}
		}
		
		public function testRetrieve()
		{
			$cssClass = 'category';
			$image = 'category.jpg';
			
			$category = $this->category->create(
				array (
					'categoryCssClass' => $cssClass,
					'categoryImage' => $image
				)
			);
			
			$categoryRetrieved = $this->category->retrieve($category->id);
			
			$this->assertEquals($categoryRetrieved->cssClass, $cssClass);
			$this->assertEquals($categoryRetrieved->image, $image);
		}
		
		public function testSave()
		{
			$cssClass = 'category';
			$image = 'category.jpg';
			
			$category = $this->category->create(
				array (
					'categoryCssClass' => $cssClass,
					'categoryImage' => $image
				)
			);
			
			$categoryRetrieved = $this->category->retrieve($category->id);
			$categoryRetrieved->cssClass = "new-$cssClass";
			$categoryRetrieved->image = "new-$image";
			$categoryRetrieved->save();
			
			$categorySaved = $this->category->retrieve($categoryRetrieved->id);
			
			$this->assertEquals($categoryRetrieved->cssClass, $categorySaved->cssClass);
			$this->assertEquals($categoryRetrieved->image, $categorySaved->image);
		}
		
		public function testReadonlyFields()
		{
			$category = $this->category->create();
			
			$categoryRetrieved = $this->category->retrieve($category->id);
			$categoryRetrieved->productCount = 1;
			$categoryRetrieved->deepProductCount = 1;
			$categoryRetrieved->save();
			
			$categorySaved = $this->category->retrieve($categoryRetrieved->id);
			
			$this->assertEquals(0, $categorySaved->productCount);
			$this->assertEquals(0, $categorySaved->deepProductCount);
		}
		
		public function testBooleanFields()
		{
			$category = $this->category->create();
			
			$this->assertSame(false, $category->featured);
			$this->assertSame(true, $category->webPosActive);
		}
		
		public function testDelete()
		{
			$category = $this->category->create(
				array (
					'categoryCssClass' => 'category',
					'categoryImage' => 'category.jpg'
				)
			);
			
			$categoryRetrieved = $this->category->retrieve($category->id);
			$categoryRetrieved->delete();
			
			$this->setExpectedException('Exception');
			$categoryNonExistent = $this->category->retrieve($categoryRetrieved->id);
		}
		
		public function testAll()
		{
			// Remove existing top categories
			list ($categoriesBefore) = $this->category->all(array ('nameContains' => 'Top Category'));
			foreach ($categoriesBefore as $categoryBefore) {
				$categoryBefore->delete();
			}
			
			for ($i = 1; $i <= 3; $i++) {
				$this->category->create(
					array (
						'categoryName' => array ('en' => "Top Category $i"),
						'categoryImage' => "topcategory$i.jpg"
					)
				);
			}
			
			list ($categories, $count) = $this->category->all(array (
				'nameContains' => 'Top Category',
				'limit' => 2,
				'offset' => 1,
				'fields' => 'categoryId,categoryName'
			));
			
			$this->assertEquals(3, $count);
			$this->assertEquals(2, count($categories));
			
			for ($j = 1; $j <= 2; $j++) {
				$this->assertEquals('Top Category '.($j + 1), $categories[$j - 1]->name->EN);
				$this->assertTrue(!isset($categories[$j - 1]->image));
			}
		}
	}
?>