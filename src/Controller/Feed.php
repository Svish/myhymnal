<?php

class Controller_Feed extends CachedController
{
	function get($type)
	{
		echo new View_Feed($type);
	}
}