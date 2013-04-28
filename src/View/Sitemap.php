<?php

/**
 * @see http://www.sitemaps.org/protocol.html
 */
class View_Sitemap extends View
{
	protected $_layout = FALSE;
	protected $_accept = array(
		'application/xml',
		'text/xml',
		);

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
		foreach(Model_Song::find_all() as $song)
		{
			$lastmod = new DateTime($song->lastmod);
			$url[] = array
			(
				'loc' => $song->url,
				'lastmod' => $lastmod->format(DateTime::W3C),
			);
		}

		$this->url = $url;
		Timer::stop();
	}

	protected function toString($mime)
	{
		if( ! in_array($mime, $this->_accept))
			return parent::toString($mime);

		header('content-type: '.$mime.'; charset=utf-8');
		return $this->render();
	}

}