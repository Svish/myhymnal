<?php

class View_Book extends View
{
	public function init($id, $key = NULL)
	{
		$this->book = Model_Book::get($id);
		$this->title = $this->book->name;
	}
}