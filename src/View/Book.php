<?php

class View_Book extends View
{
	public function __construct(Model_Book $book)
	{
		Timer::start(__METHOD__);
		
		$this->book = $book;
		$this->title = $this->book->title;
		$this->canonical = $this->book->url;
		$this->description = 'Lyrics and chords for songs in the book \''.$this->book->title.'\'. Clean and simple.';

		Timer::stop();
	}
}