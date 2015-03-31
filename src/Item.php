<?php
namespace Recipe_Finder;

class Item
{
	protected $name;
	protected $amount;
	protected $unit;
	protected $expiration;

	public function setName($name = "")
	{
		if (empty($name)) {
			throw new \Exception("Name can't be empty");
		} else {
			$this->name = $name;
		}
	}

	public function getName()
	{
		return $this->name;
	}

	public function setAmount($amount = 0)
	{
		$this->amount = floatval($amount);
	}

	public function increaseAmount($amount = 0)
	{
		$this->amount += floatval($amount);
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function setUnit($unit = "")
	{
		if (empty($unit) || !in_array($unit, array('of','grams','ml','slices'))) {
			throw new \Exception("Unit can be only of, grams, ml or slices");
		} else {
			$this->unit = $unit;
		}
	}

	public function getUnit()
	{
		return $this->unit;
	}

	public function setExpiration($expiration = "")
	{
		//this should be set in the php.ini
		date_default_timezone_set('Australia/Sydney'); 

		//we replace / for - so strtotime can understand d/m/y
		if (empty($expiration)) {
			//if expiration is empty we assume that the item is not going to expire any time soon
			$this->expiration = strtotime('next year');
		} else {
			$expiration_formatted = str_replace("/", "-", $expiration);
			$expiration_time = strtotime($expiration_formatted);
			if ($expiration_time === false) {
				//there was a problem parsing this date
				throw new \Exception("Expiration date should be in the following format dd/mm/yyyy");
			} else {
				$this->expiration = $expiration_time;
			}
		}
	}

	public function isExpired()
	{
		//this should be set in the php.ini
		date_default_timezone_set('Australia/Sydney'); 

		return $this->expiration < time();
	}

	public function getExpiration()
	{
		return $this->expiration;
	}
}