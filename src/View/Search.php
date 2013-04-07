<?php

class View_Search extends View
{
	public function __construct(array $songs = array(), $term)
	{
		Timer::start(__METHOD__);
		$this->title = $term.' - Search';
		$this->term = $term;
		$this->songs = $songs;

		foreach($this->songs as $song)
			$song->title = preg_replace('/'.preg_quote($this->term, '/').'/i', '[$0]', $song->title);
		Timer::stop();
	}
}