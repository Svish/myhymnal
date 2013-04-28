<?php

class Controller_Sitemap extends Controller
{
	function get()
	{
		echo new View_Sitemap();
	}
}