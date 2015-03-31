<?php
namespace Recipe_Finder;

class Fridge
{
	protected $items;

	public function loadFridge($file = "")
	{
		if (empty($file)) {
			throw new \Exception("Filename can't be empty");
			return;
		}
		$data = $this->loadCSVData($file);
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
}