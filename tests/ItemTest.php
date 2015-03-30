<?php
class ItemTest extends \PHPUnit_Framework_TestCase
{
	public function testSetName()
	{
		$item = new Recipe_Finder\Item();
		$item->setName('test_name');
		$this->assertEquals('test_name',$item->getName());
	}
}
