<?php

class View_Book extends View
{
	public function __construct($id, $key = NULL)
	{
		Timer::start(__METHOD__);
		$this->book = Model_Book::get($id);
		$this->title = $this->book->name;
		Timer::stop();
	}
}