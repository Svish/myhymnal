<?php

class View_Search extends View
{
	public function init(array $songs = array(), $term)
	{
		$this->title = $term.' - Search';
		$this->term = $term;
		$this->songs = $songs;

		foreach($this->songs as $song)
			$song->title = preg_replace('/'.preg_quote($this->term, '/').'/i', '[$0]', $song->title);
	}
}