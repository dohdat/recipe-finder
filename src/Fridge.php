<?php
namespace Recipe_Finder;

class Fridge
{
	protected $items;

	public function loadFridge($file = "")
	{
		if (empty($file)) {
			throw new Exception("Filename can't be empty");
			return;
		}
		$data = $getCSVData($file);
	}

	public function getCSVData($file = "")
	{
		$file_handle = @fopen($file, "r");
		if ($file_handle === false) {
			throw new Exception("The file couldn't be open");
			return;
		}

		$data = fgetcsv($file_handle);
		if (is_null($data) || $data === false) {
			throw new Exception("The file is empty or couldnt be formatted");
			return;
		}
		
		fclose($file_handle);
		return $data;
	}
}