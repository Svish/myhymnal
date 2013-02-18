<?php

class View_BrowseSongs extends View
{
	public function init(array $songs = array(), $term = NULL)
	{
		$this->title = 'Songs';
		$this->term = $term;
		$this->songs = $songs;

		if($this->term !== NULL)
			foreach($this->songs as $song)
				$song->title = preg_replace('/'.preg_quote($this->term, '/').'/i', '[$0]', $song->title);
	}
}