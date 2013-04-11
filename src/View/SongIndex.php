
<?php

class View_SongIndex extends View
{
	public function __construct()
	{
		Timer::start(__METHOD__);
		$this->title = 'Songs';
		$this->songs = Model_Song::find_all(TRUE);
		$this->unfinished = Model_Song::find_all(FALSE);
		Timer::stop();
	}
}