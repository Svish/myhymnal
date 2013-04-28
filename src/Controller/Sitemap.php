<?php

class Controller_Sitemap extends CachedController
{
	function get()
	{
		echo new View_Sitemap();
	}
}