<?php

namespace Seliton\Client;

class PageTestCase extends \PHPUnit_Framework_TestCase
{
	public function testCreate()
	{
		$title = array ('en' => 'Test');
		$content = array ('en' => 'Content');
		$seoTitle = array ('en' => 'SEO Title');
		$seoKeywords = array ('en' => 'SEO Keywords');
		$seoDescription = array ('en' => 'SEO Description');
		$cssClass = 'test';
		$isActive = true;
		$page = Page::create(
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

		$page = Page::create(
			array (
				'pageTitle' => $title,
				'pageCssClass' => $cssClass,
			)
		);

		$pageRetrieved = Page::retrieve($page->id);

		$this->assertEquals($title['en'], $pageRetrieved->title->EN);
		$this->assertEquals($cssClass, $pageRetrieved->cssClass);
	}

	public function testSave()
	{
		$title = array ('en' => 'Test');
		$cssClass = 'test';
		$isActive = true;

		$page = Page::create(
			array (
				'pageTitle' => $title,
				'pageCssClass' => $cssClass,
				'pageIsActive' => $isActive,
			)
		);

		$pageRetrieved = Page::retrieve($page->id);
		$pageRetrieved->cssClass = "updated-$cssClass";
		$pageRetrieved->isActive = !$isActive;
		$pageRetrieved->save();

		$pageSaved = Page::retrieve($pageRetrieved->id);

		$this->assertEquals($pageRetrieved->cssClass, $pageSaved->cssClass);
		$this->assertEquals($pageRetrieved->isActive, $pageSaved->isActive);
	}

	public function testDelete()
	{
		$page = Page::create();

		$pageRetrieved = Page::retrieve($page->id);
		$pageRetrieved->delete();

		$this->setExpectedException('Exception');
		$pageNonExistent = Page::retrieve($pageRetrieved->id);
	}

	public function testAll()
	{
		// Remove existing test pages
		list ($pagesBefore) = Page::all(array ('titleContains' => 'Test'));
		foreach ($pagesBefore as $pageBefore) {
			$pageBefore->delete();
		}

		for ($i = 1; $i <= 3; $i++) {
			Page::create(
				array (
					'pageTitle' => array ('en' => "Test $i"),
					'pageCssClass' => "test-$i"
				)
			);
		}

		list ($pages, $count) = Page::all(array (
			'titleContains' => 'Test',
			'limit' => 2,
			'offset' => 1,
			'fields' => 'pageId,pageTitle'
		));

		$this->assertEquals(3, $count);
		$this->assertEquals(2, count($pages));

		for ($j = 1; $j <= 2; $j++) {
			$this->assertEquals('Test '.($j + 1), $pages[$j - 1]->title->EN);
			$this->assertTrue(!isset($pages[$j - 1]->cssClass));
		}
	}
}
