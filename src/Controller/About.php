<?php

class Controller_About extends Controller
{
	function get()
	{
		echo new View_About();
	}
}