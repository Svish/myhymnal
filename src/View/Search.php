<?php

class View_Search extends View
{
	public function __construct($term)
	{
		Timer::start(__METHOD__);

		$songs = Model_Song::search($term);

		$this->title = $term.' - Search';
		$this->term = $term;
		$this->result = $songs;

		Timer::stop();
	}

	public function when_json()
	{
		$list = $this->result;

		foreach($list as &$song)
			$song = array(
				'label' => $song->title, 
				'value' => $song->url,
				);

		return $list;
	}
}