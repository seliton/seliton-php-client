<?php

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

class AttributeType {
	const TEXT = 'text';
	const SELECT = 'select';
	const RADIO_BUTTONS = 'radio_buttons';
	const TEXT_ML = 'text_ml';
	const MULTIPLE_SELECT = 'multiple_select';
	const CHECKBOX = 'checkbox';
}

class AttributeValidator {
		const NONE = 'none';
		const NUMBER = 'number';
		const ISBN = 'isbn';
		const GTIN = 'gtin';
		const MANUFACTURER_SKU = 'manufacturer_sku';
		const DEPTH = 'depth';
		const WIDTH = 'width';
		const HEIGHT = 'height';
		const EAN13 = 'ean13';
		const BARCODE = 'barcode';
	}

	class AttributeFilterWidget {
		const NONE = 'none';
		const MANUALLY_SET_INTERVALS = 'manually_set_intervals';
		const AUTOMATIC_INTERVALS = 'automatic_intervals';
		const DRILLDOWN_AUTOMATIC_INTERVALS = 'drilldown_automatic_intervals';
		const SLIDER_INTERVAL = 'slider_interval';
		const SELECT = 'select';
		const LINKS = 'links';
		const CHECKBOXES_OR = 'checkboxes_or';
		const CHECKBOXES_AND = 'checkboxes_and';
		const TEXT = 'text';
		const CATEGORY = 'category';
		const BRAND = 'brand';
		const CHECKBOX = 'checkbox';
		const COLOR = 'color';
		const LINKS_COMPACT = 'links_compact';
		const THUMB = 'thumb';
	}