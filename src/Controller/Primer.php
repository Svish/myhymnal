<?php

class Controller_Primer
{
	function get()
	{
		header('content-type: text/plain; charset=utf-8');

		// Clear cache
		Cache::delete();

		// Load sitemap
		$sitemap = WEBROOT_ABS.'sitemap.xml';
		$doc = DOMDocument::loadXML(HTTP::get($sitemap));

		// Touch all urls
		foreach($doc->getElementsByTagName('loc') as $loc)
		{
			$url = $loc->nodeValue;
			echo $url.PHP_EOL;
			echo HTTP::head($url).PHP_EOL;
			flush();
		}
	}
}