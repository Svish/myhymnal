<?php

return array
(
	'_' => array
	(
		'title' => 'My Hymnal',
		'subtitle' => 'Lyrics and chords. Clean and correct.',
		'base' => WEBROOT,
		'base_abs' => 'http://'.$_SERVER['HTTP_HOST'].WEBROOT,
		'isProduction' => ENV == 'prod',
		'clickyId' => 100581712,
		'sid' => substr(RID, 4),
	),
);
