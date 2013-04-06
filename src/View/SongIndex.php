<?php

class View_SongIndex extends View
{
	public function init()
	{
		$this->title = 'Songs';
		$this->songs = Model_Song::find_all();
	}
}