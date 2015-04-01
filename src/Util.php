<?php
namespace Recipe_Finder;

class Util
{
	static function getCSVData($file = "")
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