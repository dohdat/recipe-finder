<?php
class UtilTest extends \PHPUnit_Framework_TestCase
{
	function testGetCSVData()
	{
		$util = new Recipe_Finder\Util();
		$data = array(
			array("bread","10","slices","25/07/2015")
		);
		$file_data = $util::getCSVData('tests/fridge.csv');
		$this->assertEquals($data, $file_data);
	}

	function testGetJsonData()
	{
		$util = new Recipe_Finder\Util();
		$data = array(
			array(
				'name' => 'grilled cheese on toast',
				'ingredients' => array(
					array(
						'item' => 'bread',
						'amount' => '2',
						'unit' => 'slices'
					)
				)
			)
		);
		$file_data = $util::getJsonData('tests/recipes.json');
		$this->assertEquals($data, $file_data);
	}
}