<?php

namespace Seliton\Client;

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
		'referrerID'
	);
}

class CustomerStatus {
	const DISABLED = 'disabled';
	const ACTIVE = 'active';
	const PENDING_APPROVAL = 'pending_approval';
	const REJECTED = 'rejected';
}
