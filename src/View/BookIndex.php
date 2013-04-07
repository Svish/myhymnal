<?php

class View_BookIndex extends View
{
	public function __construct()
	{
		Timer::start(__METHOD__);
		$this->title = 'Books';
		$this->books = Model_Book::find_all();
		Timer::stop();
	}
}