<?php
class FridgeTest extends \PHPUnit_Framework_TestCase
{
	function testLoadFridge()
	{
		$fridge = new Recipe_Finder\Fridge();
		$fridge->load('tests/fridge.csv');
		$item = new Recipe_Finder\Item();
		$item->setName('bread');
		$item->setAmount('10');
		$item->setUnit('slices');
		$item->setExpiration('25/07/2015');
		$hash_id = md5('bread');
		$items = array($hash_id => $item);
		$this->assertEquals($items, $fridge->getItems());
	}

	function testHas()
	{
		$fridge = new Recipe_Finder\Fridge();
		$fridge->load('tests/fridge.csv');
		$this->assertEquals(false, $fridge->has('cheese','10'));
		$this->assertEquals(false, $fridge->has('bread','19'));
		$this->assertEquals(true, $fridge->has('bread','8'));
	}
}