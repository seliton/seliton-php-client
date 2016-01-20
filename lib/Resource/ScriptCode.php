<?php

namespace Seliton\Client\Resource;

class ScriptCode extends Resource {
	protected static $_name = 'scriptCode';
	protected static $namePlural = 'scriptCodes';
	protected static $fields = array (
		'id',
		'position',
		'text',
		'appID',
		'name',
	);
}
