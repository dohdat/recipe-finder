<?php
use Recipe_Finder\Item;
use Recipe_Finder\Recipe;

class RecipeTest extends \PHPUnit_Framework_TestCase
{
	public function testSetName()
	{
		$recipe = new Recipe();
		$recipe->setName('test_name');
		$this->assertEquals('test_name', $recipe->getName());
	}

	public function testAddIngredient()
	{
		$recipe = new Recipe();
		$recipe->addIngredient('bread', '10', 'slices');
		$item = new Item();
		$item->setName('bread');
		$item->setAmount('10');
		$item->setUnit('slices');
		$ingredients = array($item);
		$this->assertEquals($ingredients, $recipe->getIngredients());
	}
}