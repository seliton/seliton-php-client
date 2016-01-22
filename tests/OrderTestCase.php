<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Tests;
	
	use Seliton\Client\Seliton;
	
	require_once dirname(__FILE__).'/TestCase.php';
	
	class OrderTestCase extends TestCase
	{
		protected function setUp()
		{
			$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/', static::getAccessToken());
			$this->order = $seliton->order();
		}
		
		public function testRetrieve()
		{
			$orderRetrieved = $this->order->retrieve(2048);
			
			$this->assertEquals('Flat Rate', $orderRetrieved->shippingModuleName);
			$this->assertEquals('Collect on delivery', $orderRetrieved->paymentModuleName);
			
			$this->assertEquals('49.99', $orderRetrieved->items[0]['orderItemTotal']);
			
			$this->assertEquals('Доставка', $orderRetrieved->totalLines[1]['orderTotalLineName']);
		}
		
		public function testAll()
		{
			list ($orders, $count) = $this->order->all(array (
				'limit' => 2,
				'offset' => 1,
				'fields' => 'orderId,orderPaymentModuleName',
				'order' => 'orderId:asc'
			));
			
			$this->assertEquals(284, $count);
			$this->assertEquals(2, count($orders));
			
			for ($j = 0; $j < 2; $j++) {
				$this->assertEquals('Collect on delivery', $orders[$j]->paymentModuleName);
				$this->assertTrue(!isset($orders[$j]->shippingModuleName));
			}
		}
	}
?>