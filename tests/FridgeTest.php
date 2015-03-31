<?php
class FridgeTest extends \PHPUnit_Framework_TestCase
{
	function testloadCSVData()
	{
		$fridge = new Recipe_Finder\Fridge();
		$data = array(
			array("bread","10","slices","25/12/2014")
		);
		$file_data = $fridge->loadCSVData('tests/fridge.csv');
		$this->assertEquals($data, $file_data);
	}

	function testLoadFridge()
	{
		$fridge = new Recipe_Finder\Fridge();
		$fridge->loadFridge('tests/fridge.csv');
		$item = new Recipe_Finder\Item();
		$item->setName('bread');
		$item->setAmount('10');
		$item->setUnit('slices');
		$item->setExpiration('25/12/2014');
		$hash_id = md5('bread');
		$items = array($hash_id => $item);
		$this->assertEquals($items, $fridge->getItems());
	}
}