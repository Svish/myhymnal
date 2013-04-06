<?php

class Controller_Book
{
	function get($id)
	{
		echo new View_Book($id);
	}
}