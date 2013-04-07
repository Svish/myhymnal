<?php

class View_SongIndex extends View
{
	public function __construct()
	{
		$this->title = 'Songs';
		$this->songs = Model_Song::find_all();
	}
}