<?php
namespace Recipe_Finder;

class Recipe
{
	protected $name;
	protected $ingredients = array();

	public function setName($name = "")
	{
		if (empty($name)) {
			throw new \Exception("Name can't be empty");
		} else {
			$this->name = $name;
		}
	}

	public function addIngredient($name = "", $amount = 0, $unit = "")
	{
		$item = new Item();
		$item->setName($name);
		$item->setAmount($amount);
		$item->setUnit($unit);
		$this->ingredients[] = $item;
	}
}