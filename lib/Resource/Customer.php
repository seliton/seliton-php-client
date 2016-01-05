<?php

namespace Seliton\Client\Resource;

class Customer extends Resource {
	protected static $_name = 'customer';
	protected static $namePlural = 'customers';
	protected static $fields = array (
		'id',
		'email',
		'password',
		'status',
		'groupID',
		'bonusPoints',
		'referrerID',
		'addresses',
	);

	protected function convertField($name, $value)
	{
		if ($name == 'addresses') {
			$addresses = array ();
			foreach ($value as $address) {
				$addresses[] = array (
					'customerAddressID' => $address->customerAddressID,
					'customerID' => $address->customerID,
					'customerAddressIsDefaultBilling' => $address->customerAddressIsDefaultBilling,
					'customerAddressIsDefaultShipping' => $address->customerAddressIsDefaultShipping,
					'customerAddressFirstName' => $address->customerAddressFirstName,
					'customerAddressLastName' => $address->customerAddressLastName,
					'customerAddressCompany' => $address->customerAddressCompany,
					'customerAddressLine1' => $address->customerAddressLine1,
					'customerAddressLine2' => $address->customerAddressLine2,
					'customerAddressCity' => $address->customerAddressCity,
					'cityID' => $address->cityID,
					'stateCode' => $address->stateCode,
					'countryCode' => $address->countryCode,
					'customerAddressZip' => $address->customerAddressZip,
					'customerAddressPhone' => $address->customerAddressPhone,
					'customerAddressFax' => $address->customerAddressFax,
					'customerAddressVatNumber' => $address->customerAddressVatNumber,
					'customerAddressTaxNumber' => $address->customerAddressTaxNumber,
					'customerAddressTaxOffice' => $address->customerAddressTaxOffice,
					'customerAddressPersonalNumber' => $address->customerAddressPersonalNumber,
					'customerAddressInvoiceContactPerson' => $address->customerAddressInvoiceContactPerson,
					'customerAddressIsCompany' => $address->customerAddressIsCompany,
					'customerAddressBankName' => $address->customerAddressBankName,
					'customerAddressBankAccount' => $address->customerAddressBankAccount,
				);
			}
			return $addresses;
		}
		return parent::convertField($name, $value);
	}
}
