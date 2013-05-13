
<?php

class View_Spotify extends View
{
	public function __construct()
	{
		Timer::start(__METHOD__);
		$this->title = 'Spotify';
		$this->songs = Model_Spotify::find_all();
		$this->tracks = implode(',', Util::pluck('id', $this->songs));
		Timer::stop();
	}
}