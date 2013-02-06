<?php

class View_BrowseSongs extends View
{
	public function init()
	{
		$this->title = 'Songs';
		$this->term = isset($_GET['term']) ? $_GET['term'] : NULL;
		$this->songs = Model_Song::get_list($this->term);

		if($this->term !== NULL)
			foreach($this->songs as $song)
				$song->title = preg_replace('/'.preg_quote($this->term, '/').'/i', '[$0]', $song->title);
	}
}