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

		if($bytes == 0)
			return sprintf('%.2f '.$symbols[0], 0);

		$exp = floor(log($bytes) / log(1024));
		return sprintf('%.2f '.$symbols[$exp], $bytes/pow(1024, floor($exp)));
	}

	/**
	 * Cleans strings for URI compatibility.
	 *
	 * @link http://cubiq.org/the-perfect-php-clean-url-generator
	 */
	public function toAscii($subject, $replace = "'`´", $delimiter='-')
	{
		if( ! empty($replace))
			$subject = str_replace(str_split($replace), ' ', $subject);

		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $subject);
		$clean = preg_replace("%[^-/+|\w ]%", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("%[/_|+ -]+%", $delimiter, $clean);

		return $clean;
	}
}