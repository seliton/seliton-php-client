<?php
	/**
	 * Copyright (c) 2015 Mirchev Ideas Ltd. All rights reserved.
	 */
	
	namespace Seliton\Client\Resource;
	
	class Attribute extends Resource {
		protected static $_name = 'attribute';
		protected static $namePlural = 'attributes';
		protected static $fields = array (
			'id',
			'name',
			'unit',
			'code',
			'type',
			'validator',
			'isFilterable',
			'filterWidget',
			'filterWidgetShowCounts',
			'filterWidgetHideIrrelevant',
			'filterMoveOutWhenApplied',
			'isSearchable',
			'isComparable',
			'applyToAllProducts',
			'showAfterDescription',
			'showInTab',
			'showInQuickView',
			'showOnHover',
			'sort',
		);
	}
?>