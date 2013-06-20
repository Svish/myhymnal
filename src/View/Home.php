<?php

class View_Home extends View
{
	public function __construct()
	{
		Timer::start(__METHOD__);
		$this->songs = Model_Song::list_last_updated();
		$this->top = View::factory('TopPages')->render(FALSE);
		Timer::stop();
	}
}