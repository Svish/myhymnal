<?php

class Controller_Book extends Controller
{
	function get($id, $slug = NULL)
	{
		$book = Model_Book::get($id);

		if($book === FALSE)
			throw new HTTP_Exception('Book not found.', 404);

		if($slug === NULL || $slug !== $book->slug)
			HTTP::redirect(301, $book->url);
		
		echo new View_Book($book);
	}
}