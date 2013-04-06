<?php

class View_BookIndex extends View
{
	public function init()
	{
		$this->title = 'Books';
		$this->books = Model_Book::find_all();
	}
}