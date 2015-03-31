<?php
namespace Recipe_Finder;

class Fridge
{
	protected $items = array();

	public function loadFridgeFile($file = "")
	{
		//set the index of the file in case format change
		$index = array(
			'name' => 0,
			'amount' => 1,
			'unit' => 2,
			'expiration' => 3
		);

		if (empty($file)) {
			throw new \Exception("Filename can't be empty");
			return;
		}

		$data = $this->loadCSVData($file);
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
					} catch (Exception $e) {
						echo 'There was an error loading line '.$line.' '.$e->getMessage();
					}
				}
			}
		}
	}

	public function itemHash($name)
	{
		return md5($name);
	}

	public function loadCSVData($file = "")
	{
		$file_handle = fopen($file, "r");
		if ($file_handle === false) {
			throw new \Exception("The file couldn't be open");
			return;
		}
		$data = array();
		while ($line = fgetcsv($file_handle)) {
			$data[] = $line;
		}
		fclose($file_handle);

		return $data;
	}

	public function getItems()
	{
		return $this->items;
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
		return true;
	}
}