<?php

class View_Home extends View
{
	public function init()
	{
		$this->songs = View::factory('SongIndex')->render(FALSE);
		$this->books = View::factory('BookIndex')->render(FALSE);
	}
}