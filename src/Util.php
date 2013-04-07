<?php

class Util
{
	public static function pluck($property, array $subject)
	{
		$array = array();
		foreach($subject as $s)
			$array[] = $s->$property;
		return $array;
	}

	public function bytes_to_human($bytes)
	{
		$symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
		$exp = floor(log($bytes) / log(1024));

		return sprintf('%.2f '.$symbols[$exp], $bytes/pow(1024, floor($exp)));
	}
}