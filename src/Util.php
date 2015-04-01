<?php
namespace Recipe_Finder;

/**
 * Util
 * Class with some utilities to handle the files data
 *
 * @author Guillermo Gette <guilermogette@gmail.com>
 */

class Util
{
	/**
	 * Get the data inside the CSV file
	 *
	 * @param string $file the location of the CSV
	 * @return array 
	 */
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

	/**
	 * Get the data inside the JSON file
	 *
	 * @param string $file the location of the json file
	 * @return array 
	 */
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