<?php

return array
(
	'date' => array
		(
			'rss' => function($date = NULL, $m = NULL)
				{
					$date = $m !== NULL
						? new DateTime($m->render($date))
						: new DateTime($date);
					return $date->format(DATE_RSS);
				},

			'atom' => function($date = NULL, $m = NULL)
				{
					$date = $m !== NULL
						? new DateTime($m->render($date))
						: new DateTime($date);
					return $date->format(DATE_ATOM);
				},
		),

	'_' => array
		(
			'favicon' => 'favicon.ico',
			'title' => 'My Hymnal',
			'description' => function($more = NULL)
				{
					return "Lyrics and chords$more. Clean and correct.";
				},

			'base' => WEBROOT,
			'base_abs' => 'http://'.$_SERVER['HTTP_HOST'].WEBROOT,
			
			'isProduction' => ENV == 'prod',
			
			'clickyId' => 100581712,
			
			'sid' => substr(RID, 4),
			
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
		),
);
