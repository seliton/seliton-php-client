<?php

namespace Seliton\Client;

class CustomerTestCase extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		// Remove existing test customers
		list ($customers) = Customer::all(array ('emailContains' => 'test'));
		foreach ($customers as $customer) {
			$customer->delete();
		}
	}

	public function testCreate()
	{
		$email = 'test@example.com';
		$password = 'password';
		$status = CustomerStatus::ACTIVE;
		$groupID = 1;
		$bonusPoints = 0;
		$referrerID = null;
		$addresses = array (
			array (
				'customerAddressFirstName' => 'First1',
				'customerAddressLastName' => 'Last1',
				'countryID' => 225,
			),
			array (
				'customerAddressFirstName' => 'First2',
				'customerAddressLastName' => 'Last2',
				'countryID' => 225,
			),
		);

		$customer = Customer::create(
			array (
				'customerEmail' => $email,
				'customerPassword' => $password,
				'customerStatus' => $status,
				'customerGroupID' => $groupID,
				'customerBonusPoints' => $bonusPoints,
				'customerReferrerID' => $referrerID,
				'customerAddresses' => $addresses,
			)
		);

		$this->assertNotNull($customer->id);
		$this->assertEquals($email, $customer->email);
		$this->assertEquals($status, $customer->status);
		$this->assertEquals($groupID, $customer->groupID);
		$this->assertEquals($bonusPoints, $customer->bonusPoints);
		$this->assertEquals($referrerID, $customer->referrerID);

		for ($i = 0; $i < count($addresses); $i++) {
			$customerAddress = $customer->addresses[$i];
			$this->assertEquals(
				$addresses[$i]['customerAddressFirstName'],
				$customerAddress['customerAddressFirstName']
			);
			$this->assertEquals(
				$addresses[$i]['customerAddressLastName'],
				$customerAddress['customerAddressLastName']
			);
			$this->assertEquals(
				$addresses[$i]['countryID'],
				$customerAddress['countryID']
			);
		}
	}

	public function testRetrieve()
	{
		$email = 'test@example.com';
		$status = CustomerStatus::ACTIVE;
		$groupID = 1;

		$customer = Customer::create(
			array (
				'customerEmail' => $email,
				'customerStatus' => $status,
				'customerGroupID' => $groupID,
			)
		);

		$customerRetrieved = Customer::retrieve($customer->id);

		$this->assertEquals($email, $customerRetrieved->email);
		$this->assertEquals($status, $customerRetrieved->status);
	}

	public function testSave()
	{
		$email = 'test@example.com';
		$status = CustomerStatus::ACTIVE;
		$groupID = 1;
		$addresses = array (
			array (
				'customerAddressFirstName' => 'First1',
				'customerAddressLastName' => 'Last1',
				'countryID' => 225,
			),
			array (
				'customerAddressFirstName' => 'First2',
				'customerAddressLastName' => 'Last2',
				'countryID' => 225,
			),
		);

		$customerCreated = Customer::create(
			array (
				'customerEmail' => $email,
				'customerStatus' => $status,
				'customerGroupID' => $groupID,
				'customerAddresses' => $addresses,
			)
		);

		$customerRetrieved = Customer::retrieve($customerCreated->id);
		$customerRetrieved->email = "updated.$email";
		$customerRetrieved->status = CustomerStatus::DISABLED;
		$customerRetrieved->addresses = array (
			array (
				'customerAddressFirstName' => 'UpdatedFirst1',
				'customerAddressLastName' => 'UpdatedLast1',
				'countryID' => 225,
			),
			array (
				'customerAddressFirstName' => 'UpdatedFirst2',
				'customerAddressLastName' => 'UpdatedLast2',
				'countryID' => 225,
			),
		);
		$customerRetrieved->save();

		$customerSaved = Customer::retrieve($customerRetrieved->id);

		$this->assertEquals($customerRetrieved->email, $customerSaved->email);
		$this->assertEquals($customerRetrieved->status, $customerSaved->status);

		$this->assertEquals(2, count($customerSaved->addresses));
		for ($i = 0; $i < count($customerRetrieved->addresses); $i++) {
			$customerAddress = $customerSaved->addresses[$i];
			$this->assertEquals(
				$customerRetrieved->addresses[$i]['customerAddressFirstName'],
				$customerAddress['customerAddressFirstName']
			);
			$this->assertEquals(
				$customerRetrieved->addresses[$i]['customerAddressLastName'],
				$customerAddress['customerAddressLastName']
			);
			$this->assertEquals(
				$customerRetrieved->addresses[$i]['countryID'],
				$customerAddress['countryID']
			);
		}
	}

	public function testDelete()
	{
		$email = 'test@example.com';
		$groupID = 1;

		$customer = Customer::create(
			array (
				'customerEmail' => $email,
				'customerGroupID' => $groupID,
			)
		);

		$customerRetrieved = Customer::retrieve($customer->id);
		$customerRetrieved->delete();

		$this->setExpectedException('Exception');
		$customerNonExistent = Customer::retrieve($customerRetrieved->id);
	}

	public function testAll()
	{
		// Remove existing test customers
		list ($customersBefore) = Customer::all(array ('emailContains' => 'test'));
		foreach ($customersBefore as $customerBefore) {
			$customerBefore->delete();
		}

		for ($i = 1; $i <= 3; $i++) {
			Customer::create(
				array (
					'customerEmail' => "test.$i@example.com",
					'customerGroupID' => 1,
					'customerStatus' => CustomerStatus::ACTIVE,
				)
			);
		}

		list ($customers, $count) = Customer::all(array (
			'emailContains' => 'test',
			'limit' => 2,
			'offset' => 1,
			'fields' => 'customerId,customerEmail'
		));

		$this->assertEquals(3, $count);
		$this->assertEquals(2, count($customers));

		for ($j = 1; $j <= 2; $j++) {
			$this->assertEquals('test.'.($j + 1).'@example.com', $customers[$j - 1]->email);
			$this->assertTrue(!isset($customers[$j - 1]->status));
		}
	}
}
