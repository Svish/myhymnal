<?php

class View_Home extends View
{
	public function __construct()
	{
		Timer::start(__METHOD__);
		$this->songs = View::factory('SongIndex')->render(FALSE);
		$this->books = View::factory('BookIndex')->render(FALSE);
		Timer::stop();
	}
}