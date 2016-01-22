<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Tests;
	
	use Seliton\Client\Seliton;
	
	require_once dirname(__FILE__).'/TestCase.php';
	
	class SelitonTestCase extends TestCase
	{
		public function testCreateWithStaticMethod()
		{
			Seliton::setApiUrl('http://dev-1.myseliton.com/api/v1/');
			Seliton::setAccessToken(static::getAccessToken());
			
			$page = Seliton::page()->create();
			
			$this->assertNotNull($page->id);
		}
		
		public function testFactoryCreate()
		{
			$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/', static::getAccessToken());
			
			$page = $seliton->page()->create();
			
			$this->assertNotNull($page->id);
		}
		
		public function testFactoryRetrieve()
		{
			$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/', static::getAccessToken());
			
			$page = $seliton->page()->create();
			$pageRetrieved = $seliton->page()->retrieve($page->id);
			
			$this->assertNotNull($pageRetrieved->id);
		}
		
		public function testFactorySave()
		{
			$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/', static::getAccessToken());
			
			$page = $seliton->page()->create();
			$pageRetrieved = $seliton->page()->retrieve($page->id);
			$pageRetrieved->cssClass = 'updated-page';
			$pageRetrieved->save();
			
			$pageSaved = $seliton->page()->retrieve($pageRetrieved->id);
			
			$this->assertEquals($pageRetrieved->cssClass, $pageSaved->cssClass);
		}
		
		public function testFactoryDelete()
		{
			$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/', static::getAccessToken());
			
			$page = $seliton->page()->create();
			
			$pageRetrieved = $seliton->page()->retrieve($page->id);
			$pageRetrieved->delete();
			
			$this->setExpectedException('Exception');
			$pageNonExistent = $seliton->page()->retrieve($pageRetrieved->id);
		}
		
		public function testFactoryAll()
		{
			$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/', static::getAccessToken());
			
			// Remove existing test pages
			list ($pagesBefore) = $seliton->page()->all(
				array ('titleContains' => 'Test')
			);
			foreach ($pagesBefore as $pageBefore) {
				$pageBefore->delete();
			}
			
			for ($i = 1; $i <= 3; $i++) {
				$seliton->page()->create(
					array (
						'pageTitle' => array ('en' => "Test"),
					)
				);
			}
			
			list (, $count) = $seliton->page()->all(array (
				'titleContains' => 'Test',
			));
			
			$this->assertEquals(3, $count);
		}
	}
?>