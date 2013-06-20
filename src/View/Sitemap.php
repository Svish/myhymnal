<?php

/**
 * @see http://www.sitemaps.org/protocol.html
 */
class View_Sitemap extends XmlView
{
	public function __construct()
	{
		Timer::start(__METHOD__);

		// Indexes
		$url = array
		(
			array
			(
				'loc' => '',
				'priority' => '0.1',
			),
			array
			(
				'loc' => 'books',
				'priority' => '0.2',
			),
			array
			(
				'loc' => 'songs',
				'priority' => '0.2',
			),
		);

		// Songs
		$songs = array_merge(Model_Song::list_all(), Model_Song::list_unfinished());
		foreach($songs as $song)
		{
			$lastmod = new DateTime($song->lastmod);
			$url[] = array
			(
				'loc' => $song->url,
				'lastmod' => $lastmod->format(DateTime::W3C),
				'priority' => '1.0',
			);

			// Transposed
			foreach(array_keys(\Geekality\Transposer::$SCALES) as $k)
				$url[] = array
				(
					'loc' => $song->url.'?key='.urlencode($k),
					'lastmod' => $lastmod->format(DateTime::W3C),
					'priority' => '0.8',
				);
		}

		$this->url = $url;
		Timer::stop();
	}
}