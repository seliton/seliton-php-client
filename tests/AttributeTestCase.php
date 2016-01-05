<?php

namespace Seliton\Client;

class AttributeTestCase extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/');
		$this->_attribute = $seliton->attribute();

		// Remove existing test attributes
		list ($attributes) = $this->_attribute->all(array ('nameContains' => 'Test'));
		foreach ($attributes as $attribute) {
			$attribute->delete();
		}
	}

	public function testCreate()
	{
		$name = 'Test';
		$unit = 'Unit';
		$code = 'test';
		$type = AttributeType::TEXT;
		$validator = AttributeValidator::NONE;
		$isFilterable = true;
		$filterWidget = AttributeFilterWidget::TEXT;
		$filterWidgetShowCounts = true;
		$filterWidgetHideIrrelevant = true;
		$filterMoveOutWhenApplied = false;
		$isSearchable = true;
		$isComparable = true;
		$applyToAllProducts = false;
		$showAfterDescription = true;
		$showInTab = false;
		$showInQuickView = true;
		$showOnHover = true;
		$sort = 200;

		$attribute = $this->_attribute->create(
			array (
				'attributeName' => array ('en' => $name),
				'attributeUnit' => array ('en' => $unit),
				'attributeCode' => $code,
				'attributeType' => $type,
				'attributeValidator' => $validator,
				'attributeIsFilterable' => $isFilterable,
				'attributeFilterWidget' => $filterWidget,
				'attributeFilterWidgetShowCounts' => $filterWidgetShowCounts,
				'attributeFilterWidgetHideIrrelevant' => $filterWidgetHideIrrelevant,
				'attributeFilterMoveOutWhenApplied' => $filterMoveOutWhenApplied,
				'attributeIsSearchable' => $isSearchable,
				'attributeIsComparable' => $isComparable,
				'attributeApplyToAllProducts' => $applyToAllProducts,
				'attributeShowAfterDescription' => $showAfterDescription,
				'attributeShowInTab' => $showInTab,
				'attributeShowInQuickView' => $showInQuickView,
				'attributeShowOnHover' => $showOnHover,
				'attributeSort' => $sort,
			)
		);

		$this->assertNotNull($attribute->id);

		$this->assertEquals($name, $attribute->name->EN);
		$this->assertEquals($unit, $attribute->unit->EN);
		foreach (array (
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
			) as $field) {
			$this->assertEquals($$field, $attribute->$field);
		}
	}

	public function testRetrieve()
	{
		$name = 'Test';
		$code = 'test';
		$type = AttributeType::TEXT;
		$validator = AttributeValidator::NONE;

		$attribute = $this->_attribute->create(
			array (
				'attributeName' => array ('en' => $name),
				'attributeCode' => $code,
				'attributeType' => $type,
				'attributeValidator' => $validator
			)
		);

		$attributeRetrieved = $this->_attribute->retrieve($attribute->id);

		$this->assertEquals($attributeRetrieved->type, $type);
		$this->assertEquals($attributeRetrieved->validator, $validator);
	}

	public function testSave()
	{
		$name = 'Test';
		$code = 'test';
		$type = AttributeType::TEXT;
		$validator = AttributeValidator::NONE;

		$attribute = $this->_attribute->create(
			array (
				'attributeName' => array ('en' => $name),
				'attributeCode' => $code,
				'attributeType' => $type,
				'attributeValidator' => $validator
			)
		);

		$attributeRetrieved = $this->_attribute->retrieve($attribute->id);
		$attributeRetrieved->type = AttributeType::SELECT;
		$attributeRetrieved->save();

		$attributeSaved = $this->_attribute->retrieve($attributeRetrieved->id);

		$this->assertEquals($attributeRetrieved->type, $attributeSaved->type);
	}

	public function testDelete()
	{
		$attribute = $this->_attribute->create(
			array (
				'attributeName' => array ('en' => 'Test'),
				'attributeCode' => 'test',
				'attributeType' => AttributeType::TEXT,
				'attributeValidator' => AttributeValidator::NONE
			)
		);

		$attributeRetrieved = $this->_attribute->retrieve($attribute->id);
		$attributeRetrieved->delete();

		$this->setExpectedException('Exception');
		$attributeNonExistent = $this->_attribute->retrieve($attributeRetrieved->id);
	}

	public function testAll()
	{
		for ($i = 1; $i <= 3; $i++) {
			$this->_attribute->create(
				array (
					'attributeName' => array ('en' => "Test $i"),
					'attributeCode' => "test_$i",
					'attributeType' => AttributeType::TEXT,
					'attributeValidator' => AttributeValidator::NONE,
				)
			);
		}

		list ($attributes, $count) = $this->_attribute->all(array (
			'nameContains' => 'Test',
			'limit' => 2,
			'offset' => 1,
			'fields' => 'attributeId,attributeName'
		));

		$this->assertEquals(3, $count);
		$this->assertEquals(2, count($attributes));

		for ($j = 1; $j <= 2; $j++) {
			$this->assertEquals('Test '.($j + 1), $attributes[$j - 1]->name->EN);
			$this->assertTrue(!isset($attributes[$j - 1]->code));
		}
	}
}