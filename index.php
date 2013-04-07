<?php

# Constants
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('WEBROOT', $_SERVER['BASE']);
define('EXT', '.php');
define('SID', uniqid('sid_'));


# Timezone
date_default_timezone_set('Europe/Oslo');

# AutoLoader
require 'vendor/autoload.php';

# Hooks
ToroHook::add('404', function() {
    echo 'Not found';
});

# Go!
Timer::start('Request', isset($_GET['toro_uri']) ? $_GET['toro_uri'] : NULL);
Toro::serve(array(
    '/' => 'Controller_Home',
    '/books' => 'Controller_BookIndex',
    '/book/:number' => 'Controller_Book',
    '/songs' => 'Controller_SongIndex',
    '/song/:number(?:/([A-G][♯♭]?))?' => 'Controller_Song',
    '/search' => 'Controller_Search',
    '/debug/:alphanum' => 'Controller_Debug',
), isset($_GET['toro_uri']) ? $_GET['toro_uri'] : NULL);

# Store debug stats
$stats = array(
	'timers' => Timer::result(),
	'memory' => array(
		'usage' => Util::bytes_to_human(memory_get_usage()),
		'usage_real' => Util::bytes_to_human(memory_get_usage(TRUE)),
		'peak_usage' => Util::bytes_to_human(memory_get_peak_usage()),
		'peak_usage_real' => Util::bytes_to_human(memory_get_peak_usage(TRUE)),
		),
	);
Cache::instance()->delete('sid_*', 1*60*60);
Cache::instance()->set(SID, $stats);