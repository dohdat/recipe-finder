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
}