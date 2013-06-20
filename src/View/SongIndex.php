
<?php

class View_SongIndex extends View
{
	public function __construct()
	{
		Timer::start(__METHOD__);
		$this->title = 'Songs';
		$this->songs = Model_Song::list_all();
		$this->unfinished = Model_Song::list_unfinished();
		Timer::stop();
	}
}