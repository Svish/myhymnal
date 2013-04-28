<?php

class Controller_Book
{
	function get($id, $slug = NULL)
	{
		Timer::start(__METHOD__, func_get_args());

		$book = Model_Book::get($id);

		if($book === FALSE)
			throw new Exception('Book not found.', 404);

		if($slug === NULL || $slug !== $book->slug)
		{
			header('Location: '.WEBROOT.$book->url);
			exit;
		}
		else
			echo new View_Book($book);

		Timer::stop();
	}
}