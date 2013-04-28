<?php 

return array(
	
    '/' => 'Controller_Home',

    '/songs' => 'Controller_SongIndex',
    '/:number(?:/:alphanum)?' => 'Controller_Song',
    '/random' => 'Controller_Song',

    '/books' => 'Controller_BookIndex',
    '/book/:number(?:/:alphanum)?' => 'Controller_Book',

    '/search' => 'Controller_Search',

    '/about' => 'Controller_About',

    '/sitemap.xml' => 'Controller_Sitemap',
    '/feed\.(atom|rss)' => 'Controller_Feed',

    '/debug/:alphanum' => 'Controller_Debug',

);