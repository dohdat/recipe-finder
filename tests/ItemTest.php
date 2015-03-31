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
		$item = new Recipe_Finder\Item();
		$item->setExpiration('asdas/asdas/2015');
		$item->getExpiration();
    }

    public function testIncreaseAmount()
    {
    	$item = new Recipe_Finder\Item();
		$item->setAmount('10');
		$item->increaseAmount('5');
		$this->assertEquals('15',$item->getAmount());
    }

    /**
     * @expectedException        \Exception
     * @expectedExceptionMessage Unit can be only of, grams, ml or slices
     */
    public function testSetUnit()
    {
    	$item = new Recipe_Finder\Item();
		$item->setUnit('asdas');
    }
}
