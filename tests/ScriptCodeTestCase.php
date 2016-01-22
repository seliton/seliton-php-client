<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Tests;
	
	use Seliton\Client\Resource\ScriptCode;
	use Seliton\Client\Seliton;
	use Seliton\Client\Resource\Enum;
	
	require_once dirname(__FILE__).'/TestCase.php';
	
	class ScriptCodeTestCase extends TestCase
	{
		/**
		 * @var ScriptCode
		 */
		protected $scriptCode;
		
		protected function setUp()
		{
			$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/', static::getAccessToken());
			$this->scriptCode = $seliton->scriptCode();
		}
		
		public function testCreate()
		{
			$position = Enum\ScriptCodePosition::END_OF_BODY;
			$text = 'test';
			$appID = '123';
			$name = 'Test';
			$scriptCode = $this->scriptCode->create(
				array (
					'scriptCodePosition' => $position,
					'scriptCodeText' => $text,
					'scriptCodeAppID' => $appID,
					'scriptCodeName' => $name,
				)
			);
			
			$this->assertNotNull($scriptCode->id);
			$this->assertEquals($position, $scriptCode->position);
			$this->assertEquals($text, $scriptCode->text);
			$this->assertEquals($appID, $scriptCode->appID);
			$this->assertEquals($name, $scriptCode->name);
		}
		
		public function testRetrieve()
		{
			$text = 'test';
			$name = 'Test';
			$scriptCode = $this->scriptCode->create(
				array (
					'scriptCodeText' => $text,
					'scriptCodeName' => $name,
				)
			);
			
			$scriptCodeRetrieved = $this->scriptCode->retrieve($scriptCode->id);
			
			$this->assertEquals($text, $scriptCodeRetrieved->text);
			$this->assertEquals($name, $scriptCodeRetrieved->name);
		}
		
		public function testSave()
		{
			$text = 'test';
			$name = 'Test';
			$scriptCode = $this->scriptCode->create(
				array (
					'scriptCodeText' => $text,
					'scriptCodeName' => $name,
				)
			);
			
			$scriptCodeRetrieved = $this->scriptCode->retrieve($scriptCode->id);
			$scriptCodeRetrieved->text = "updated $text";
			$scriptCodeRetrieved->name = "updated $name";
			$scriptCodeRetrieved->save();
			
			$scriptCodeSaved = $this->scriptCode->retrieve($scriptCodeRetrieved->id);
			
			$this->assertEquals($scriptCodeRetrieved->text, $scriptCodeSaved->text);
			$this->assertEquals($scriptCodeRetrieved->name, $scriptCodeSaved->name);
		}
		
		public function testDelete()
		{
			$scriptCode = $this->scriptCode->create(
				array (
					'scriptCodeText' => 'test',
					'scriptCodeName' => 'Test',
				)
			);
			
			$scriptCodeRetrieved = $this->scriptCode->retrieve($scriptCode->id);
			$scriptCodeRetrieved->delete();
			
			$this->setExpectedException('Exception');
			$scriptCodeNonExistent = $this->scriptCode->retrieve($scriptCodeRetrieved->id);
		}
		
		public function testAll()
		{
			// Remove existing test scriptCodes
			list ($scriptCodesBefore) = $this->scriptCode->all(array (
				'nameContains' => 'Test'
			));
			foreach ($scriptCodesBefore as $scriptCodeBefore) {
				$scriptCodeBefore->delete();
			}
			
			for ($i = 1; $i <= 3; $i++) {
				$this->scriptCode->create(
					array (
						'scriptCodeText' => "test$i",
						'scriptCodeName' => "Test $i"
					)
				);
			}
			
			list ($scriptCodes, $count) = $this->scriptCode->all(array (
				'nameContains' => 'Test',
				'limit' => 2,
				'offset' => 1,
				'fields' => 'scriptCodeId,scriptCodeName'
			));
			
			$this->assertEquals(3, $count);
			$this->assertEquals(2, count($scriptCodes));
			
			for ($j = 1; $j <= 2; $j++) {
				$this->assertEquals('Test '.($j + 1), $scriptCodes[$j - 1]->name);
				$this->assertTrue(!isset($scriptCodes[$j - 1]->text));
			}
		}
	}
?>