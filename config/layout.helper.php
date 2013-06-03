<?php

return array
	(
		'favicon' => 'favicon.ico',
		'title' => 'My Hymnal',
		'description' => function($more = NULL)
			{
				return "Lyrics and chords$more. Transposable, clean and correct.";
			},

		'base' => BASE_URI,
		'base_abs' => WEBROOT,
		
		'env' => array
		(
			'prod' => ENV == 'prod',
			'dev' => ENV == 'dev',
		),
		
		'rid' => RID,
		
		'stylesheet' => array
		(
			'//fonts.googleapis.com/css?family=Inconsolata&amp;subset=latin,latin-ext',
			'//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/dark-hive/jquery-ui.min.css',
			'_/styles.css',
		),
		
		'script' => array
		(
			'//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
			'//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js',
			'_/global.js',
		),

		'feed' => array
		(
			'atom' => 'feed.atom',
			'rss' => 'feed.rss',
		),
	);
