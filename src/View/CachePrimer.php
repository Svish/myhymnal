<?php

class View_CachePrimer extends JsonEnabledView
{
	public function __construct()
	{
		$this->title = 'Primer';

		// Load sitemap
		$sitemap = WEBROOT.'sitemap.xml';
		$sitemap = HTTP::get($sitemap);

		// Parse
		$doc = new DOMDocument();
		$doc->loadXML($sitemap);

		// Get urls
		$url = array();
		foreach($doc->getElementsByTagName('loc') as $loc)
			$url[] = $loc->nodeValue;

		$this->url = $url;
	}
}