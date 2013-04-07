<?php

class View_Book extends View
{
	public function __construct($id, $key = NULL)
	{
		$this->book = Model_Book::get($id);
		$this->title = $this->book->name;
	}
}