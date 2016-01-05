<?php

namespace Seliton\Client\Tests;

use Seliton\Client\Seliton;

class BrandTestCase extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/');
		$this->brand = $seliton->brand();
	}

	public function testCreate()
	{
		$name = array ('en' => 'Brand');
		$description = array ('en' => 'Description');
		$seoTitle = array ('en' => 'SEO Title');
		$seoKeywords = array ('en' => 'SEO Keywords');
		$seoDescription = array ('en' => 'SEO Description');
		$website = 'brand.com';
		$image = 'brand.jpg';
		$imageWidth = 200;
		$imageHeight = 150;
		$productCount = 1;
		$sort = 1000;
		$brand = $this->brand->create(
			array (
				'brandName' => $name,
				'brandDescription' => $description,
				'brandSEOTitle' => $seoTitle,
				'brandSEOKeywords' => $seoKeywords,
				'brandSEODescription' => $seoDescription,
				'brandWebsite' => $website,
				'brandImage' => $image,
				'brandImageWidth' => $imageWidth,
				'brandImageHeight' => $imageHeight,
				'brandProductCount' => $productCount,
				'brandSort' => $sort,
			)
		);

		$this->assertNotNull($brand->id);
		$this->assertEquals($name['en'], $brand->name->EN);
		$this->assertEquals($description['en'], $brand->description->EN);
		$this->assertEquals($seoTitle['en'], $brand->seoTitle->EN);
		$this->assertEquals($seoKeywords['en'], $brand->seoKeywords->EN);
		$this->assertEquals($seoDescription['en'], $brand->seoDescription->EN);
		$this->assertEquals($website, $brand->website);
		$this->assertEquals("http://dev-1.myseliton.com/$image", $brand->image);
		$this->assertEquals(0, $brand->imageWidth);
		$this->assertEquals(0, $brand->imageHeight);
		$this->assertEquals(0, $brand->productCount);
		$this->assertEquals($sort, $brand->sort);
	}

	public function testRetrieve()
	{
		$website = 'brand.com';
		$image = 'brand.jpg';

		$brand = $this->brand->create(
			array (
				'brandWebsite' => $website,
				'brandImage' => $image
			)
		);

		$brandRetrieved = $this->brand->retrieve($brand->id);

		$this->assertEquals($brandRetrieved->website, $website);
		$this->assertEquals($brandRetrieved->image, "http://dev-1.myseliton.com/$image");
	}

	public function testSave()
	{
		$website = 'brand.com';
		$image = 'brand.jpg';

		$brand = $this->brand->create(
			array (
				'brandWebsite' => $website,
				'brandImage' => $image
			)
		);

		$brandRetrieved = $this->brand->retrieve($brand->id);
		$brandRetrieved->website = "new $website";
		$brandRetrieved->image = "new $image";
		$brandRetrieved->save();

		$brandSaved = $this->brand->retrieve($brandRetrieved->id);

		$this->assertEquals($brandRetrieved->website, $brandSaved->website);
		$this->assertEquals("http://dev-1.myseliton.com/{$brandRetrieved->image}", $brandSaved->image);
	}

	public function testDelete()
	{
		$brand = $this->brand->create(
			array (
				'brandWebsite' => 'brand.com',
				'brandImage' => 'brand.jpg'
			)
		);

		$brandRetrieved = $this->brand->retrieve($brand->id);
		$brandRetrieved->delete();

		$this->setExpectedException('Exception');
		$brandNonExistent = $this->brand->retrieve($brandRetrieved->id);
	}

	public function testAll()
	{
		// Remove existing top brands
		list ($brandsBefore) = $this->brand->all(array ('nameContains' => 'Top Brand'));
		foreach ($brandsBefore as $brandBefore) {
			$brandBefore->delete();
		}

		for ($i = 1; $i <= 3; $i++) {
			$this->brand->create(
				array (
					'brandName' => array ('en' => "Top Brand $i"),
					'brandImage' => "topbrand$i.jpg"
				)
			);
		}

		list ($brands, $count) = $this->brand->all(array (
			'nameContains' => 'Top Brand',
			'limit' => 2,
			'offset' => 1,
			'fields' => 'brandId,brandName'
		));

		$this->assertEquals(3, $count);
		$this->assertEquals(2, count($brands));

		for ($j = 1; $j <= 2; $j++) {
			$this->assertEquals('Top Brand '.($j + 1), $brands[$j - 1]->name->EN);
			$this->assertTrue(!isset($brands[$j - 1]->image));
		}
	}
}