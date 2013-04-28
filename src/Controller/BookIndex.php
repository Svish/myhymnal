<?php

class Controller_BookIndex extends Controller
{
	function get()
	{
		echo new View_BookIndex();
	}
}