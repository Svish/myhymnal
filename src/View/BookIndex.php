<?php

class View_BookIndex extends View
{
	public function __construct()
	{
		$this->title = 'Books';
		$this->books = Model_Book::find_all();
	}
}