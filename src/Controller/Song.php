<?php

class Controller_Song
{
	function get($id)
	{
		echo new View_Song($id);
	}
}