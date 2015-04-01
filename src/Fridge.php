<?php
namespace Recipe_Finder;

/**
 * Fridge
 * Class that has a collection of Items with some logic around if those items are available
 *
 * @author Guillermo Gette <guilermogette@gmail.com>
 */
class Fridge
{
	protected $items = array();
	protected $load_errors = array();

	/**
	 * Load the fridge by a CSV file
	 *
	 * @param string $file the route to the file
	 */
	public function load($file = "")
	{
		//set the index of the file in case format change
		$index = array(
			'name' => 0,
			'amount' => 1,
			'unit' => 2,
			'expiration' => 3
		);
		$data = Util::getCSVData($file);
		if (count($data) > 0) {
			foreach ($data as $line => $item_line) {
				//unique id for each item to speed up search
				$hash_id = $this->itemHash($item_line[$index['name']]);
				if (isset($this->items[$hash_id])) {
					//item is in the fridge already
					$this->items[$hash_id]->increaseAmount($item_line[$index['amount']]);
				} else {
					try {
						$item = new Item();
						$item->setName($item_line[$index['name']]);
						$item->setAmount($item_line[$index['amount']]);
						$item->setUnit($item_line[$index['unit']]);
						$item->setExpiration($item_line[$index['expiration']]);
						$this->items[$hash_id] = $item;
					} catch (\Exception $e) {
						//save this in an array so we can control the output in the console
						$this->load_errors[] = "Line {$line} of {$file} couldn't be loaded: ".$e->getMessage();
					}
				}
			}
		}
	}

	/**
	 * Create a hash for the item
	 *
	 * @param string $name
	 * @return string
	 */
	public static function itemHash($name)
	{
		return md5($name);
	}

	/**
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @return array
	 */
	public function getLoadErrors()
	{
		return $this->load_errors;
	}

	/**
	 * Check if an element is available in the fridge (availability, amount, expiration)
	 *
	 * @param string $item_name
	 * @param string $amount
	 * @return string
	 */
	public function has($item_name, $amount)
	{
		$hash = $this->itemHash($item_name);
		$amount = floatval($amount);
		if (!isset($this->items[$hash])) {
			return false;
		}
		$item = $this->items[$hash];
		if ($item->getAmount() < $amount) {
			return false;
		}
		if ($item->isExpired()) {
			return false;
		}
		return true;
	}
}