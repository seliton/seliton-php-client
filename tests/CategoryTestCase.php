<?php

namespace Seliton\Client;

class CategoryTestCase extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		Seliton::setApiUrl('http://dev-1.myseliton.com/api/v1/');
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
		$status = Category::STATUS_VISIBLE;
		$featured = false;
		$includeProductsFromSubs = true;
		$cssClass = 'category';
		$webPosActive = true;
		$webPosPosition = 500;
		$webPosButtonColor = 'red';
		$webPosButtonTextColor = 'green';
		$iconImage = 'icon.jpg';
		$sort = 1000;

		$category = Category::create(
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

		$category = Category::create(
			array (
				'categoryCssClass' => $cssClass,
				'categoryImage' => $image
			)
		);

		$categoryRetrieved = Category::retrieve($category->id);

		$this->assertEquals($categoryRetrieved->cssClass, $cssClass);
		$this->assertEquals($categoryRetrieved->image, $image);
	}

	public function testSave()
	{
		$cssClass = 'category';
		$image = 'category.jpg';

		$category = Category::create(
			array (
				'categoryCssClass' => $cssClass,
				'categoryImage' => $image
			)
		);

		$categoryRetrieved = Category::retrieve($category->id);
		$categoryRetrieved->cssClass = "new-$cssClass";
		$categoryRetrieved->image = "new-$image";
		$categoryRetrieved->save();

		$categorySaved = Category::retrieve($categoryRetrieved->id);

		$this->assertEquals($categoryRetrieved->cssClass, $categorySaved->cssClass);
		$this->assertEquals($categoryRetrieved->image, $categorySaved->image);
	}

	public function testReadonlyFields()
	{
		$category = Category::create();

		$categoryRetrieved = Category::retrieve($category->id);
		$categoryRetrieved->productCount = 1;
		$categoryRetrieved->deepProductCount = 1;
		$categoryRetrieved->save();

		$categorySaved = Category::retrieve($categoryRetrieved->id);

		$this->assertEquals(0, $categorySaved->productCount);
		$this->assertEquals(0, $categorySaved->deepProductCount);
	}

	public function testBooleanFields()
	{
		$category = Category::create();

		$this->assertSame(false, $category->featured);
		$this->assertSame(true, $category->webPosActive);
	}

	public function testDelete()
	{
		$category = Category::create(
			array (
				'categoryCssClass' => 'category',
				'categoryImage' => 'category.jpg'
			)
		);

		$categoryRetrieved = Category::retrieve($category->id);
		$categoryRetrieved->delete();

		$this->setExpectedException('Exception');
		$categoryNonExistent = Category::retrieve($categoryRetrieved->id);
	}

	public function testAll()
	{
		// Remove existing top categories
		list ($categoriesBefore) = Category::all(array ('nameContains' => 'Top Category'));
		foreach ($categoriesBefore as $categoryBefore) {
			$categoryBefore->delete();
		}

		for ($i = 1; $i <= 3; $i++) {
			Category::create(
				array (
					'categoryName' => array ('en' => "Top Category $i"),
					'categoryImage' => "topcategory$i.jpg"
				)
			);
		}

		list ($categories, $count) = Category::all(array (
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