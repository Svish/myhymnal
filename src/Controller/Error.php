<?php

class Controller_Error
{
	function get(Exception $e)
	{
		echo new View_Error($e);
	}
}