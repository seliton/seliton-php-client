<?php

namespace Seliton\Client\Tests;

use Seliton\Client\Seliton;

require_once dirname(__FILE__).'/TestCase.php';

class PageTestCase extends TestCase
{
	protected function setUp()
	{
		$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/');
		$this->page = $seliton->page();
	}

	public function testCreate()
	{
		$title = array ('en' => 'Test');
		$content = array ('en' => 'Content');
		$seoTitle = array ('en' => 'SEO Title');
		$seoKeywords = array ('en' => 'SEO Keywords');
		$seoDescription = array ('en' => 'SEO Description');
		$cssClass = 'test';
		$isActive = true;
		$page = $this->page->create(
			array (
				'pageTitle' => $title,
				'pageContent' => $content,
				'pageSEOTitle' => $seoTitle,
				'pageSEOKeywords' => $seoKeywords,
				'pageSEODescription' => $seoDescription,
				'pageCssClass' => $cssClass,
				'pageIsActive' => $isActive,
			)
		);

		$this->assertNotNull($page->id);
		$this->assertEquals($title['en'], $page->title->EN);
		$this->assertEquals($content['en'], $page->content->EN);
		$this->assertEquals($seoTitle['en'], $page->seoTitle->EN);
		$this->assertEquals($seoKeywords['en'], $page->seoKeywords->EN);
		$this->assertEquals($seoDescription['en'], $page->seoDescription->EN);
		$this->assertEquals($cssClass, $page->cssClass);
		$this->assertEquals($isActive, $page->isActive);
	}

	public function testRetrieve()
	{
		$title = array ('en' => 'Test');
		$cssClass = 'test';

		$page = $this->page->create(
			array (
				'pageTitle' => $title,
				'pageCssClass' => $cssClass,
			)
		);

		$pageRetrieved = $this->page->retrieve($page->id);

		$this->assertEquals($title['en'], $pageRetrieved->title->EN);
		$this->assertEquals($cssClass, $pageRetrieved->cssClass);
	}

	public function testSave()
	{
		$title = array ('en' => 'Test');
		$cssClass = 'test';
		$isActive = true;

		$page = $this->page->create(
			array (
				'pageTitle' => $title,
				'pageCssClass' => $cssClass,
				'pageIsActive' => $isActive,
			)
		);

		$pageRetrieved = $this->page->retrieve($page->id);
		$pageRetrieved->cssClass = "updated-$cssClass";
		$pageRetrieved->isActive = !$isActive;
		$pageRetrieved->save();

		$pageSaved = $this->page->retrieve($pageRetrieved->id);

		$this->assertEquals($pageRetrieved->cssClass, $pageSaved->cssClass);
		$this->assertEquals($pageRetrieved->isActive, $pageSaved->isActive);
	}

	public function testDelete()
	{
		$page = $this->page->create();

		$pageRetrieved = $this->page->retrieve($page->id);
		$pageRetrieved->delete();

		$this->setExpectedException('Exception');
		$pageNonExistent = $this->page->retrieve($pageRetrieved->id);
	}

	public function testAll()
	{
		// Remove existing test pages
		list ($pagesBefore) = $this->page->all(array (
			'titleContains' => 'Test',
			'access_token' => static::getAccessToken()
		));
		foreach ($pagesBefore as $pageBefore) {
			$pageBefore->delete();
		}

		for ($i = 1; $i <= 3; $i++) {
			$this->page->create(
				array (
					'pageTitle' => array ('en' => "Test $i"),
					'pageCssClass' => "test-$i"
				)
			);
		}

		list ($pages, $count) = $this->page->all(array (
			'titleContains' => 'Test',
			'limit' => 2,
			'offset' => 1,
			'fields' => 'pageId,pageTitle',
			'access_token' => static::getAccessToken()
		));

		$this->assertEquals(3, $count);
		$this->assertEquals(2, count($pages));

		for ($j = 1; $j <= 2; $j++) {
			$this->assertEquals('Test '.($j + 1), $pages[$j - 1]->title->EN);
			$this->assertTrue(!isset($pages[$j - 1]->cssClass));
		}
	}
}
