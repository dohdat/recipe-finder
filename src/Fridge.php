<?php
namespace Recipe_Finder;

class Fridge
{
	protected $items = array();
	protected $load_errors = array();

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

	public function itemHash($name)
	{
		return md5($name);
	}

	public function getItems()
	{
		return $this->items;
	}

	public function getLoadErrors()
	{
		return $this->load_errors;
	}

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