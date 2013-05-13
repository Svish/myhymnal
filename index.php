<?php

# Constants
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('CONFROOT', DOCROOT.'config'.DIRECTORY_SEPARATOR);
define('WEBROOT', $_SERVER['BASE']);
define('WEBROOT_ABS', 'http://'.$_SERVER['HTTP_HOST'].WEBROOT);
define('ENV', $_SERVER['SITE_ENV']);
define('RID', uniqid());


# Timezone
date_default_timezone_set('Europe/Oslo');

# Locale
setlocale(LC_ALL, 'en_US.utf-8', 'eng');

# AutoLoader
require 'vendor/autoload.php';

// Get path
$uri = isset($_GET['path_uri'])
    ? $_GET['path_uri']
    : NULL;
unset($_GET['path_uri']);


// Handle request
Timer::start('Request', array($uri));
Website::init('Controller_Error')
    ->serve($uri);
Cache::delete('rid', NULL, 30*60);
Cache::set('rid', RID, Timer::result());
