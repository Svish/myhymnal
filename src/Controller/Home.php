<?php

class Controller_Home extends Controller
{
	function get()
	{
		echo new View_Home();
	}
}