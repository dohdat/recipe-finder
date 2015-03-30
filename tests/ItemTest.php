<?php
class ItemTest extends \PHPUnit_Framework_TestCase
{
	public function testSetName()
	{
		$item = new Recipe_Finder\Item();
		$item->setName('test_name');
		$this->assertEquals('test_name',$item->getName());
	}

	public function testSetExpiration()
	{
		date_default_timezone_set('Australia/Sydney'); 

		$item = new Recipe_Finder\Item();
		$item->setExpiration('30/03/2015');
		$this->assertEquals('1427634000',$item->getExpiration());
	}

	/**
     * @expectedException        \Exception
     * @expectedExceptionMessage Expiration date should be in the following format dd/mm/yyyy
     */
    public function testSetExpirationException()
    {
        date_default_timezone_set('Australia/Sydney'); 

		$item = new Recipe_Finder\Item();
		$item->setExpiration('asdas/asdas/2015');
		$item->getExpiration();
    }
}
