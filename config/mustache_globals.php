<?php

return array
(
	'site' => array
	(
		'title' => 'My Hymnal',
		'subtitle' => 'Lyrics and chords. Clean and correct.',
		'base' => WEBROOT,
		'base_abs' => 'http://'.$_SERVER['HTTP_HOST'].WEBROOT,
		'isProduction' => $_SERVER['SITE_ENV'] == 'prod',
		'clickyId' => 100581712,
		'sid' => substr(SID, 4),
	),
);
