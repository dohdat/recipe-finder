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
}