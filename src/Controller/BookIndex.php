<?php

class Controller_BookIndex
{
	function get()
	{
		echo new View_BookIndex();
	}
}