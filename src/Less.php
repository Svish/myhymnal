<?php

class Less
{
	public static function compile($inputFile, $outputFile)
	{
		Timer::start(__METHOD__, array(str_replace(DOCROOT, NULL, $inputFile), str_replace(DOCROOT, NULL, $outputFile)));

		$cacheFile = $inputFile.".cache";
		$cache = file_exists($cacheFile)
			? unserialize(file_get_contents($cacheFile))
			: $inputFile;

		$less = new lessc;
		$less->setFormatter("compressed");
		$newCache = $less->cachedCompile($cache);

		if (!is_array($cache) || $newCache["updated"] > $cache["updated"])
		{
			file_put_contents($cacheFile, serialize($newCache));
			file_put_contents($outputFile, $newCache['compiled']);
		}

		Timer::stop();
	}
}