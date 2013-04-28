<?php

class Controller_Home extends CachedController
{
	function get()
	{
		echo new View_Home();
	}
}