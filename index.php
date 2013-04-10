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
Cache::delete('sid_*', 30*60);
Timer::start('Request', array(isset($_GET['toro_uri']) ? $_GET['toro_uri'] : NULL));
Toro::serve(array(
    '/' => 'Controller_Home',
    '/books' => 'Controller_BookIndex',
    '/book/:number' => 'Controller_Book',
    '/songs' => 'Controller_SongIndex',
    '/song/:number' => 'Controller_Song',
    '/search' => 'Controller_Search',
    '/about' => 'Controller_About',
    '/debug/:alphanum' => 'Controller_Debug',
), isset($_GET['toro_uri']) ? $_GET['toro_uri'] : NULL);
Cache::set(SID, Timer::result());
