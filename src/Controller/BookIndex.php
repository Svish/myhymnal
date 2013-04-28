<?php

class Controller_BookIndex extends CachedController
{
	function get()
	{
		echo new View_BookIndex();
	}
}