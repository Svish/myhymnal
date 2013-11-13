<?php

# Constants
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('CONFROOT', DOCROOT.'config'.DIRECTORY_SEPARATOR);
define('BASE_URI', $_SERVER['BASE']);
define('WEBROOT', 'http://'.$_SERVER['HTTP_HOST'].BASE_URI);
define('ENV', $_SERVER['SITE_ENV']);
define('RID', uniqid());


# Timezone
date_default_timezone_set('Europe/Oslo');

# Locale
setlocale(LC_ALL, 'en_US.utf-8', 'eng');

# Encoding
mb_internal_encoding("UTF-8");

# AutoLoader
require 'vendor/autoload.php';

// Get path
$_SERVER['PATH_INFO'] = isset($_GET['path_uri']) ? $_GET['path_uri'] : NULL;
unset($_GET['path_uri']);

// Handle request
error_reporting(ENV === 'dev' ? E_ALL : 0);
Timer::start('Request', array($_SERVER['PATH_INFO']));
Website::init(array('ErrorHandler', 'handle'))->serve();
Cache::delete('rid', NULL, 30*60);
Cache::set('rid', RID, Timer::result());