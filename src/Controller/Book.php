<?php

class Controller_Book extends Controller
{
	function get($id, $slug = NULL)
	{
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
	}
}