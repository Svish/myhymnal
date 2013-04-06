<?php

class Arr
{
	public static function get($property, array $subject)
	{
		$array = array();
		foreach($subject as $s)
			$array[] = $s->$property;
		return $array;
	}
}