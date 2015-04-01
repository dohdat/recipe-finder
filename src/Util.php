<?php
namespace Recipe_Finder;

class Util
{
	static function getCSVData($file = "")
	{
		if (empty($file)) {
			throw new \Exception("Filename {$file} can't be empty");
			return;
		}
		$file_handle = @fopen($file, "r");
		if ($file_handle === false) {
			throw new \Exception("The file {$file} couldn't be open");
			return;
		}
		$data = array();
		while ($line = fgetcsv($file_handle)) {
			$data[] = $line;
		}
		fclose($file_handle);

		return $data;
	}

	static function getJsonData($file = "")
	{
		if (empty($file)) {
			throw new \Exception("Filename {$file} can't be empty");
			return;
		}
		$file_content = @file_get_contents($file);
		if ($file_content === false) {
			throw new \Exception("The file {$file} couldn't be open");
			return;
		} 
		$data = json_decode($file_content,true);
		if (is_null($data) || $data === false) {
			throw new \Exception("The content of the file {$file} must be a string in JSON format");
			return;
		}
		return $data;
	}
}