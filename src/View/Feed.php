<?php

/**
 * @see http://www.rssboard.org/rss-validator/
 * @see 
 */
class View_Feed extends XmlView
{

	public function __construct($type)
	{
		Timer::start(__METHOD__);

		$this->_accept = array("application/$type+xml");

		$item = array();

		// Songs
		foreach(Model_Song::find_last_updated() as $song)
		{
			$item[] = array
			(
				'link' => $song->url, 
				'lastmod' => $song->lastmod,
				'title' => $song->title,
				'summary' => " for the song '{$song->title}'",
			);
		}
		$this->item = $item;

		Timer::stop();
	}

	protected function toString($mime = 'text/html')
	{
		switch($mime)
		{
			case 'application/atom+xml':
				header('content-type: '.$mime.'; charset=utf-8');
				return $this->render(FALSE, 'Feed.atom');

			case 'application/rss+xml':
				header('content-type: '.$mime.'; charset=utf-8');
				return $this->render(FALSE, 'Feed.rss');

			default:
				return parent::toString($mime);
		}
	}
}